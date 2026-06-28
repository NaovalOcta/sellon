<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'subscription_order_id',
        'promotion_order_id',
        'reference_code',
        'amount',
        'payment_method',
        'status',
        'paid_at',
        'approved_by',
        'approved_at',
        'rejected_reason',
        'payment_details',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'paid_at' => 'datetime',
            'approved_at' => 'datetime',
            'payment_details' => 'array',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscriptionOrder()
    {
        return $this->belongsTo(SubscriptionOrder::class);
    }

    public function promotionOrder()
    {
        return $this->belongsTo(PromotionOrder::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function isVerified(): bool
    {
        return $this->status === 'verified';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}
