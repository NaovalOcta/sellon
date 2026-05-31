<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function toggle($product_id)
    {
        $user_id = Auth::id();
        $favorite = Favorite::where('user_id', $user_id)->where('product_id', $product_id)->first();

        if ($favorite) {
            $favorite->delete();
            if (request()->ajax()) {
                return response()->json(['status' => 'removed', 'message' => 'Removed from Favorites']);
            }
            return back()->with('toast_success', 'Removed from Favorites');
        } else {
            Favorite::create(['user_id' => $user_id, 'product_id' => $product_id]);
            if (request()->ajax()) {
                return response()->json(['status' => 'added', 'message' => 'Added to Favorites']);
            }
            return back()->with('toast_success', 'Added to Favorites');
        }
    }

    public function index()
    {
        $user = Auth::user();
        $products = Product::whereHas('favoritedBy', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->orderBy('created_at', 'desc')->paginate(20);

        return view('users.show_favorite-products', compact('products'));
    }
}
