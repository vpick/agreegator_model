<?php

namespace App\Providers;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Validation\Rules\Password;
use App\Interfaces\AppOrderProcessInterface;
use App\Services\NimbusApp;
use App\Services\GrowSimplee;
use App\Services\EkartApp;
use App\Services\BluedartApp;
use App\Services\SmartshipApp;
use App\Services\EcomExpressApp;
use App\Services\DTDCApp;
use App\Services\GatiApp;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        /*$this->app->bind(
			\App\Interfaces\NimbusAppInterface::class,
			\App\Services\NimbusApp::class
		);*/
		$this->app->bind(AppOrderProcessInterface::class, function ($app, $parameters) {
            $orderAssignTo = $parameters['ordersendTo'];

            $servicesByType = [
                'NimbusApp' => NimbusApp::class,
                'NimbusAppTrack' => NimbusApp::class,
                'NimbusAppSingleTrack' => NimbusApp::class,
                'NimbusAppReOrder' => NimbusApp::class,
                'NimbusManifest' => NimbusApp::class,
                'NimbusAppServiceabilitylist' => NimbusApp::class,
                'NimbusWebHook' => NimbusApp::class,
                'NimbusAppNDR' => NimbusApp::class,
                'GrowSimplee' => GrowSimplee::class,
                'E-kartApp' => EkartApp::class,
                'BluedartApp' =>BluedartApp::class,
                'SmartshipApp'=>SmartshipApp::class,
                'EcomExpressApp'=>EcomExpressApp::class,
                'DTDCApp'=>DTDCApp::class,
                'trackSingleShipment'=>BluedartApp::class,
                'GatiApp' =>GatiApp::class,
                'cancelledShipment' =>GatiApp::class,
                'GatiAppCreateWarehouse' => GatiApp::class
                // ... and other order type => service class mappings
            ];
            if (isset($servicesByType[$orderAssignTo])) 
			{
               return $app->make($servicesByType[$orderAssignTo]);
			}
        });
    }
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
		Schema::defaultStringLength(191);
		Paginator::useBootstrap();
		Password::defaults(function () {
            $rule = Password::min(8);
            return $rule->mixedCase()->uncompromised();
        });
        
        
    }
    
}
