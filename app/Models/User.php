<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\VerifyEmailNotification;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
      'name',
      'nim',
      'major',
      'email',
      'whatsapp_no',
      'role',
      'password',
      'email_verify_otp',
      'email_verify_otp_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'email_verify_otp_expires_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Generate a 6-digit OTP and set its expiration time.
     */
    public function generateEmailVerificationOtp(): string
    {
        $otp = sprintf("%06d", mt_rand(1, 999999));
        $this->forceFill([
            'email_verify_otp' => $otp,
            'email_verify_otp_expires_at' => now()->addMinutes(60),
        ])->save();

        return $otp;
    }

    /**
     * Override the default email verification notification
     * to use a custom Bahasa Indonesia template.
     */
    public function sendEmailVerificationNotification(): void
    {
        $otp = $this->generateEmailVerificationOtp();
        $this->notify(new VerifyEmailNotification($otp));
    }

    /**
     * Check if the user's email has been verified.
     */
    public function hasVerifiedEmail(): bool
    {
        return ! is_null($this->email_verified_at);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function subscriptionOrders()
    {
        return $this->hasMany(SubscriptionOrder::class);
    }

    public function userSubscription()
    {
        return $this->hasOne(UserSubscription::class)
            ->where('status', 'active')
            ->where('expires_at', '>', now());
    }

    public function isPremium(): bool
    {
        return $this->userSubscription()->exists();
    }

    public function getProductLimit(): int
    {
        return $this->isPremium() ? 100 : 20;
    }
}
