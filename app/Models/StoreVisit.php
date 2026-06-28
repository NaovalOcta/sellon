<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreVisit extends Model
{
    public $timestamps = true;
    const UPDATED_AT = null;

    protected $fillable = [
        'product_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
