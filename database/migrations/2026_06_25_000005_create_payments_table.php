<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('subscription_order_id')->nullable()->constrained('subscription_orders')->onDelete('set null');
            $table->foreignId('promotion_order_id')->nullable()->constrained('promotion_orders')->onDelete('set null');
            $table->string('reference_code')->unique();
            $table->decimal('amount', 15, 2);
            $table->enum('payment_method', ['bank_transfer', 'qris']);
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->text('rejected_reason')->nullable();
            $table->json('payment_details')->nullable(); // Option B: menyimpan metadata nama pengirim, path screenshot, dll.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
