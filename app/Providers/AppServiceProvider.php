<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Inject $pendingPaymentsCount to the navigation partial for admin users
        View::composer('partials._nav', function ($view) {
            if (Auth::check() && Auth::user()->role === 'admin') {
                $view->with('pendingPaymentsCount', Payment::where('status', 'pending')->count());
            }
        });
    }
}
