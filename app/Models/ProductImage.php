<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    protected $fillable = ['product_id', 'image_url'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getDisplayImageAttribute()
    {
        if (empty($this->image_url)) {
            return 'https://ui-avatars.com/api/?name=Image&background=F1F5F9&color=94A3B8&size=640';
        }

        if (str_starts_with($this->image_url, 'http')) {
            return $this->image_url;
        }

        if (!\Illuminate\Support\Facades\Storage::disk('public')->exists($this->image_url)) {
            return 'https://ui-avatars.com/api/?name=Image&background=F1F5F9&color=94A3B8&size=640';
        }

        return asset('uploads/' . $this->image_url);
    }
}
