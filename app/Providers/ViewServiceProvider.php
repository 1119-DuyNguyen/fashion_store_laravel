<?php

namespace App\Providers;

use App\Http\View\Composers\CartComposer;
use App\Http\View\Composers\MenuComposer;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ViewServiceProvider extends ServiceProvider
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
        View::composer('header', MenuComposer::class);
        View::composer('cart', CartComposer::class);
        //
    }
}
