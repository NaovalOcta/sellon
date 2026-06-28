<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use App\Notifications\VerifyEmailNotification;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_verification_screen_can_be_rendered(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user)->get('/verify');

        $response->assertStatus(200);
        $response->assertSee('Masukkan Kode OTP');
    }

    public function test_email_can_be_verified_with_correct_otp(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        // Generate OTP
        $otp = $user->generateEmailVerificationOtp();

        $response = $this->actingAs($user)->post('/email/verify', [
            'otp' => $otp,
        ]);

        $response->assertRedirect(route('product.index', ['view_type' => 'home']));
        $this->assertNotNull($user->fresh()->email_verified_at);
        $this->assertNull($user->fresh()->email_verify_otp);
    }

    public function test_email_cannot_be_verified_with_incorrect_otp(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        // Generate OTP
        $user->generateEmailVerificationOtp();

        $response = $this->actingAs($user)->from('/verify')->post('/email/verify', [
            'otp' => '000000', // incorrect
        ]);

        $response->assertRedirect('/verify');
        $response->assertSessionHas('toast_error', 'Kode OTP yang Anda masukkan salah.');
        $this->assertNull($user->fresh()->email_verified_at);
    }

    public function test_email_cannot_be_verified_with_expired_otp(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $otp = $user->generateEmailVerificationOtp();

        // Expire the OTP by setting the expiry in the past
        $user->forceFill([
            'email_verify_otp_expires_at' => now()->subMinutes(1),
        ])->save();

        $response = $this->actingAs($user)->from('/verify')->post('/email/verify', [
            'otp' => $otp,
        ]);

        $response->assertRedirect('/verify');
        $response->assertSessionHas('toast_error', 'Kode OTP telah kadaluarsa. Silakan kirim ulang.');
        $this->assertNull($user->fresh()->email_verified_at);
    }

    public function test_resending_verification_notification_generates_new_otp(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user)->from('/verify')->post('/verify/resend');

        $response->assertRedirect('/verify');
        $response->assertSessionHas('toast_success', 'Email verifikasi telah dikirim ulang. Cek inbox Anda.');

        $this->assertNotNull($user->fresh()->email_verify_otp);
        $this->assertNotNull($user->fresh()->email_verify_otp_expires_at);

        Notification::assertSentTo(
            $user,
            VerifyEmailNotification::class,
            function ($notification) use ($user) {
                return $notification->otp === $user->fresh()->email_verify_otp;
            }
        );
    }
}
