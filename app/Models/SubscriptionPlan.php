<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    protected $fillable = [
        'name',
        'duration_days',
        'price',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'duration_days' => 'integer',
        ];
    }

    public function subscriptionOrders()
    {
        return $this->hasMany(SubscriptionOrder::class, 'plan_id');
    }

    public function userSubscriptions()
    {
        return $this->hasMany(UserSubscription::class, 'plan_id');
    }
}
