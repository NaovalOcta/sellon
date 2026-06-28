<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionOrder;
use App\Models\PromotionPackage;
use App\Models\PromotionOrder;
use App\Models\Payment;
use App\Models\UserSubscription;
use App\Models\ProductPromotion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MonetizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed data dasar paket subscription
        $this->plan = SubscriptionPlan::create([
            'name' => 'Bulanan',
            'duration_days' => 30,
            'price' => 15000.00,
        ]);

        // Seed data dasar paket promosi
        $this->package = PromotionPackage::create([
            'name' => '3 Hari',
            'duration_days' => 3,
            'price_regular' => 5000.00,
            'price_premium' => 3500.00,
        ]);
    }

    /**
     * Test Seller non-premium tidak bisa upload lebih dari 20 produk
     */
    public function test_non_premium_cannot_upload_more_than_20_products(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        
        // Buat 20 produk awal
        Product::factory()->count(20)->create(['user_id' => $user->id]);

        $this->actingAs($user);

        // Coba upload produk ke-21
        $response = $this->post(route('product.post'), [
            'name' => 'Produk 21',
            'description' => 'Deskripsi Produk 21',
            'price' => 10000,
            'category' => 'Preloved',
            'condition' => 'Good',
            'image_urls' => [UploadedFile::fake()->image('product21.jpg')],
            'user_id' => $user->id,
        ]);

        $response->assertSessionHasErrors(['limit']);
        $this->assertEquals(20, Product::where('user_id', $user->id)->count());
    }

    /**
     * Test Grandfathering: seller lama dengan 45 produk tidak kehilangan produk,
     * tetapi tidak bisa tambah produk baru sebelum upgrade ke Premium.
     */
    public function test_grandfathered_user_retains_products_but_cannot_upload_new_ones(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        
        // Simulasikan seller lama yang sudah memiliki 45 produk sebelum fitur dirilis
        Product::factory()->count(45)->create(['user_id' => $user->id]);

        $this->actingAs($user);

        // Pastikan seluruh 45 produk tetap utuh (tidak hilang)
        $this->assertEquals(45, Product::where('user_id', $user->id)->count());

        // Coba upload produk baru ke-46 (harus dibatasi)
        $response = $this->post(route('product.post'), [
            'name' => 'Produk Baru Lama',
            'description' => 'Deskripsi',
            'price' => 5000,
            'category' => 'Food',
            'image_urls' => [UploadedFile::fake()->image('food.jpg')],
            'user_id' => $user->id,
        ]);

        $response->assertSessionHasErrors(['limit']);
        $this->assertEquals(45, Product::where('user_id', $user->id)->count());
    }

    /**
     * Test alur lengkap pendaftaran Premium Seller:
     * Order -> Submit Payment -> Verify (Approve) oleh Admin -> Subscription Aktif
     */
    public function test_subscription_flow_and_admin_approval(): void
    {
        Storage::fake('public');

        $user = User::factory()->create(['role' => 'user']);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user);

        // 1. Seller klik subscribe (membuat SubscriptionOrder)
        $response = $this->post(route('premium.subscribe'), [
            'plan_id' => $this->plan->id,
        ]);

        $order = SubscriptionOrder::first();
        $this->assertNotNull($order);
        $this->assertEquals('pending', $order->status);
        $response->assertRedirect(route('premium.confirm', ['id' => $order->id]));

        // 2. Sebelum payment dikirim, pastikan belum ada UserSubscription aktif
        $this->assertFalse($user->isPremium());
        $this->assertDatabaseMissing('user_subscriptions', ['user_id' => $user->id]);

        // 3. Seller kirim konfirmasi pembayaran
        $responsePayment = $this->post(route('premium.submit_payment', ['id' => $order->id]), [
            'sender_name' => 'Budi Santoso',
            'amount' => 15000,
            'payment_method' => 'bank_transfer',
            'transfer_time' => now()->format('Y-m-d\TH:i'),
            'screenshot' => UploadedFile::fake()->image('receipt.jpg'),
        ]);

        $payment = Payment::first();
        $this->assertNotNull($payment);
        $this->assertEquals('pending', $payment->status);
        $this->assertEquals($order->id, $payment->subscription_order_id);
        $this->assertEquals('Budi Santoso', $payment->payment_details['sender_name']);
        
        // 4. Admin melakukan approval
        $this->actingAs($admin);
        $responseApprove = $this->post(route('admin.payments.approve', ['id' => $payment->id]));

        $payment->refresh();
        $order->refresh();

        // Pastikan status payment verified & order completed & audit trail tercatat
        $this->assertEquals('verified', $payment->status);
        $this->assertEquals('completed', $order->status);
        $this->assertEquals($admin->id, $payment->approved_by);
        $this->assertNotNull($payment->approved_at);

        // Pastikan UserSubscription aktif terbuat dan user terdeteksi Premium
        $subscription = UserSubscription::where('user_id', $user->id)->first();
        $this->assertNotNull($subscription);
        $this->assertEquals('active', $subscription->status);
        $this->assertTrue($user->isPremium());
        $this->assertEquals(100, $user->getProductLimit());
    }

    /**
     * Test alur penolakan (reject) pembayaran Premium Seller oleh Admin
     */
    public function test_subscription_rejection_flow(): void
    {
        Storage::fake('public');

        $user = User::factory()->create(['role' => 'user']);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user);

        // Buat order pending
        $order = SubscriptionOrder::create([
            'user_id' => $user->id,
            'plan_id' => $this->plan->id,
            'status' => 'pending',
        ]);

        // Kirim bukti transfer
        $this->post(route('premium.submit_payment', ['id' => $order->id]), [
            'sender_name' => 'Budi Santoso',
            'amount' => 12000, // Nominal kurang
            'payment_method' => 'bank_transfer',
            'transfer_time' => now()->format('Y-m-d\TH:i'),
            'screenshot' => UploadedFile::fake()->image('receipt.jpg'),
        ]);

        $payment = Payment::first();

        // Admin menolak verifikasi
        $this->actingAs($admin);
        $responseReject = $this->post(route('admin.payments.reject', ['id' => $payment->id]), [
            'rejected_reason' => 'Nominal transfer tidak sesuai tarif.',
        ]);

        $payment->refresh();
        $order->refresh();

        // Pastikan status payment rejected & audit trail tercatat
        $this->assertEquals('rejected', $payment->status);
        $this->assertEquals('Nominal transfer tidak sesuai tarif.', $payment->rejected_reason);
        $this->assertEquals($admin->id, $payment->approved_by);

        // Pastikan order tetap pending agar seller bisa konfirmasi ulang
        $this->assertEquals('pending', $order->status);

        // Pastikan tidak ada subscription yang aktif
        $this->assertDatabaseMissing('user_subscriptions', ['user_id' => $user->id]);
        $this->assertFalse($user->isPremium());
    }

    /**
     * Test alur lengkap pembelian Promoted Listing:
     * Order -> Submit Payment -> Approve -> Iklan Aktif
     */
    public function test_promotion_flow_and_admin_approval(): void
    {
        Storage::fake('public');

        $user = User::factory()->create(['role' => 'user']);
        $product = Product::factory()->create(['user_id' => $user->id]);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user);

        // 1. Seller membuat order promosi
        $response = $this->post(route('promote.store'), [
            'product_id' => $product->id,
            'package_id' => $this->package->id,
        ]);

        $order = PromotionOrder::first();
        $this->assertNotNull($order);
        $this->assertEquals('pending', $order->status);

        // 2. Seller mengirim bukti bayar
        $this->post(route('promote.submit_payment', ['id' => $order->id]), [
            'sender_name' => 'Budi Santoso',
            'amount' => 5000,
            'payment_method' => 'qris',
            'transfer_time' => now()->format('Y-m-d\TH:i'),
            'screenshot' => UploadedFile::fake()->image('receipt.jpg'),
        ]);

        $payment = Payment::first();
        $this->assertNotNull($payment);

        // 3. Admin melakukan approval
        $this->actingAs($admin);
        $this->post(route('admin.payments.approve', ['id' => $payment->id]));

        $payment->refresh();
        $order->refresh();

        // Pastikan iklan terbuat dengan status active & order completed
        $this->assertEquals('verified', $payment->status);
        $this->assertEquals('completed', $order->status);

        $promotion = ProductPromotion::where('product_id', $product->id)->first();
        $this->assertNotNull($promotion);
        $this->assertEquals('active', $promotion->status);
        $this->assertTrue($product->isPromoted());
    }

    /**
     * Test hak akses halaman statistik (Hanya untuk Premium Seller)
     */
    public function test_premium_only_access_to_statistics(): void
    {
        $userNonPremium = User::factory()->create(['role' => 'user']);
        $userPremium = User::factory()->create(['role' => 'user']);

        // Set userPremium menjadi Premium aktif
        UserSubscription::create([
            'user_id' => $userPremium->id,
            'plan_id' => $this->plan->id,
            'payment_id' => 999, // dummy
            'status' => 'active',
            'started_at' => now(),
            'expires_at' => now()->addDays(30),
        ]);

        // Coba akses dengan non-premium (harus diredirect)
        $this->actingAs($userNonPremium);
        $responseNonPremium = $this->get(route('stats.index'));
        $responseNonPremium->assertRedirect('/premium');

        // Akses dengan premium (harus berhasil)
        $this->actingAs($userPremium);
        $responsePremium = $this->get(route('stats.index'));
        $responsePremium.assertStatus(200);
    }

    /**
     * Test Scheduler Command subscriptions:expire merubah status kedaluwarsa secara otomatis
     */
    public function test_scheduler_expires_subscriptions_and_promotions(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $product = Product::factory()->create(['user_id' => $user->id]);

        // Buat subscription aktif yang masanya sudah lewat (kedaluwarsa)
        $sub = UserSubscription::create([
            'user_id' => $user->id,
            'plan_id' => $this->plan->id,
            'payment_id' => 999,
            'status' => 'active',
            'started_at' => now()->subDays(31),
            'expires_at' => now()->subMinutes(5), // Kedaluwarsa 5 menit lalu
        ]);

        // Buat promosi aktif yang masanya sudah lewat (kedaluwarsa)
        $promo = ProductPromotion::create([
            'product_id' => $product->id,
            'user_id' => $user->id,
            'package_id' => $this->package->id,
            'payment_id' => 999,
            'status' => 'active',
            'started_at' => now()->subDays(4),
            'expires_at' => now()->subMinutes(5), // Kedaluwarsa 5 menit lalu
        ]);

        $this->assertEquals('active', $sub->status);
        $this->assertEquals('active', $promo->status);

        // Jalankan artisan command untuk expired
        $this->artisan('subscriptions:expire');

        $sub->refresh();
        $promo->refresh();

        // Pastikan status berubah menjadi expired
        $this->assertEquals('expired', $sub->status);
        $this->assertEquals('expired', $promo->status);
    }
}
