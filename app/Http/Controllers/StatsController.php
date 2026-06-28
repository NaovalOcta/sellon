<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StoreVisit;
use Illuminate\Support\Facades\Auth;

class StatsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Ambil ID semua produk milik user
        $products = Product::where('user_id', $user->id)->get();
        $productIds = $products->pluck('id');

        // 1. Total Kunjungan
        $totalVisits = StoreVisit::whereIn('product_id', $productIds)->count();

        // 2. Produk Terpopuler (Top 5 berdasarkan views)
        $popularProducts = Product::where('user_id', $user->id)
            ->withCount('storeVisits')
            ->orderBy('store_visits_count', 'desc')
            ->take(5)
            ->get();

        // 3. Tren Kunjungan 7 Hari Terakhir
        $visitsTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dateString = $date->format('Y-m-d');
            $dateLabel = $date->format('d M');
            
            $count = StoreVisit::whereIn('product_id', $productIds)
                ->whereDate('created_at', $dateString)
                ->count();

            $visitsTrend[] = [
                'label' => $dateLabel,
                'count' => $count,
            ];
        }

        return view('stats.index', compact('totalVisits', 'popularProducts', 'visitsTrend'));
    }
}
