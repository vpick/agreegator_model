<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class GlobalVariablesServiceProvider extends ServiceProvider
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
        view()->share('activeCompany', $this->getActiveCompany());
        view()->share('activeClient', $this->getActiveClient());
        view()->share('activeWarehouse', $this->getActiveWarehouse());
    }
    protected function getActiveCompany()
    {
        return session('company') ?: Auth::user()->company;
    }

    protected function getActiveClient()
    {
        return session('client') ?: Auth::user()->client;
    }

    protected function getActiveWarehouse()
    {
        return session('warehouse') ?: Auth::user()->warehouse;
    }
}
