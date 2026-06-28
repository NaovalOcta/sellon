<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserSubscription;
use App\Models\ProductPromotion;

class ExpireSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically expire active subscriptions and product promotions whose end dates have passed.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = now();

        $expiredSubscriptions = UserSubscription::where('status', 'active')
            ->where('expires_at', '<', $now)
            ->update(['status' => 'expired']);

        $expiredPromotions = ProductPromotion::where('status', 'active')
            ->where('expires_at', '<', $now)
            ->update(['status' => 'expired']);

        $this->info("Successfully expired {$expiredSubscriptions} subscriptions and {$expiredPromotions} promotions.");
    }
}
