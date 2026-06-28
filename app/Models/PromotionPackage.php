<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromotionPackage extends Model
{
    protected $fillable = [
        'name',
        'duration_days',
        'price_regular',
        'price_premium',
    ];

    protected function casts(): array
    {
        return [
            'duration_days' => 'integer',
            'price_regular' => 'decimal:2',
            'price_premium' => 'decimal:2',
        ];
    }

    public function promotionOrders()
    {
        return $this->hasMany(PromotionOrder::class, 'package_id');
    }

    public function productPromotions()
    {
        return $this->hasMany(ProductPromotion::class, 'package_id');
    }
}
