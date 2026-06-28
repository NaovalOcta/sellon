<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'category',
        'condition',
        'image_url',
        'user_id',
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
            'price' => 'decimal:2',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function favoritedBy()
    {
        return $this->hasMany(Favorite::class);
    }

    public function getDisplayImageAttribute()
    {
        if (empty($this->image_url)) {
            return 'https://ui-avatars.com/api/?name=' . urlencode($this->name ?? 'Product') . '&background=F1F5F9&color=94A3B8&size=640';
        }

        if (str_starts_with($this->image_url, 'http')) {
            return $this->image_url;
        }

        if (!\Illuminate\Support\Facades\Storage::disk('public')->exists($this->image_url)) {
             return 'https://ui-avatars.com/api/?name=' . urlencode($this->name ?? 'Product') . '&background=F1F5F9&color=94A3B8&size=640';
        }

        // Gunakan domain aktif dari request saat ini (tidak terikat APP_URL)
        return request()->root() . '/uploads/' . $this->image_url;
    }

    public function promotionOrders()
    {
        return $this->hasMany(PromotionOrder::class);
    }

    public function productPromotion()
    {
        return $this->hasOne(ProductPromotion::class)
            ->where('status', 'active')
            ->where('expires_at', '>', now());
    }

    public function isPromoted(): bool
    {
        return $this->productPromotion()->exists();
    }

    public function storeVisits()
    {
        return $this->hasMany(StoreVisit::class);
    }
}
