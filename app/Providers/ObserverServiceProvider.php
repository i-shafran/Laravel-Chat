<?php

namespace App\Providers;

use App\Models\Hedge;
use App\Models\Order;
use App\Models\Position;
use App\Models\User;
use App\Models\WMOrder;
use App\Observers\HedgeObserver;
use App\Observers\OrderObserver;
use App\Observers\PositionObserver;
use App\Observers\UserObserver;
use App\Observers\WMOrderObserver;
use Illuminate\Support\ServiceProvider;

class ObserverServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        
    }
}
