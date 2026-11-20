<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        View::composer('store.product', function (\Illuminate\View\View $vista): void {
            if (!array_key_exists('cartAction', $vista->getData())) {
                $vista->with('cartAction', route('carrito.store'));
            }
        });
    }
}
