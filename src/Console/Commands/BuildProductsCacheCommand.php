<?php

namespace IlBronza\Products\Console\Commands;

use Auth;
use IlBronza\AccountManager\Models\User;
use IlBronza\Products\Models\Order;
use IlBronza\Products\Services\OrderShowCacheWarmer;
use Illuminate\Console\Command;

class BuildProductsCacheCommand extends Command
{
    protected $signature = 'products:buildCache {--orders : Warm cache for orders.show}';

    protected $description = 'Build/warm caches for the Products package.';

    public function handle(OrderShowCacheWarmer $warmer): int
    {
        $user = User::gpc()::find(1);

        if ($this->option('orders')) {

            Auth::login($user);

            $orders = Order::gpc()::orderByDesc('updated_at')->get();

            foreach($orders as $order)
                $warmer->warm($order);

            Auth::logout();

            return self::SUCCESS;
        }

        $this->info("Nothing Warmed");

        return self::SUCCESS;
    }
}