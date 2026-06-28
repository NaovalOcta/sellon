<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionOrder extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'subscription_order_id');
    }
}
