<?php

namespace App\Services;

use App\Models\ProductPromotion;
use App\Models\Product;

class PromotionRotationService
{
    const SLOT_COUNT = 3;
    const ROTATION_INTERVAL_SECONDS = 3600; // 1 jam

    /**
     * Get the currently active promoted products based on the time rotation algorithm.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getRotatedPromotions()
    {
        // 1. Ambil semua promosi aktif (status active dan expires_at > now)
        // Urutkan berdasarkan started_at ASC untuk fairness
        $activePromotions = ProductPromotion::with('product')
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->orderBy('started_at', 'asc')
            ->get();

        $totalPromotions = $activePromotions->count();

        if ($totalPromotions === 0) {
            return collect();
        }

        // 2. Hitung rotation index
        $currentTime = time();
        $rotationIndex = floor($currentTime / self::ROTATION_INTERVAL_SECONDS) % $totalPromotions;

        // 3. Slice promoted products mulai dari rotationIndex sebanyak SLOT_COUNT (dengan wrapping)
        $selectedPromotions = collect();
        for ($i = 0; $i < self::SLOT_COUNT; $i++) {
            if ($selectedPromotions->count() >= $totalPromotions) {
                break; // Hindari duplikasi jika total promosi kurang dari SLOT_COUNT
            }
            
            $currentIndex = ($rotationIndex + $i) % $totalPromotions;
            $selectedPromotions->push($activePromotions[$currentIndex]);
        }

        // 4. Kembalikan produk-produk dari promosi terpilih
        return $selectedPromotions->map(function ($promotion) {
            return $promotion->product;
        })->filter()->values();
    }
}
