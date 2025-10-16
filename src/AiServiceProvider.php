<?php

namespace Devinosh\Ai;

use Devinosh\Ai\Contracts\AIClientInterface;
use Devinosh\Ai\Factory\AIClientFactory;
use Illuminate\Support\ServiceProvider;

class AiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {

    }

    /**
     * Register the application services.
     */
    public function register()
    {
        //configs
        $this->mergeConfigFrom(
            __DIR__ . '/config/ai.php' , 'ai'
        );

        $this->publishes([
            __DIR__ . '/config/ai.php' => config_path('ai.php') ,
        ] , 'ai');


        $this->app->bind(AIClientInterface::class, function ($app) {
            $provider = config('ai.default');
            return AIClientFactory::make($provider);
        });

    }
}
