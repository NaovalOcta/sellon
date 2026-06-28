<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromotionOrder extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'package_id',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function package()
    {
        return $this->belongsTo(PromotionPackage::class, 'package_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'promotion_order_id');
    }
}
