<?php

namespace Riazxrazor\Payumoney;


use Illuminate\Support\ServiceProvider;

class PayumoneyServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $dist = __DIR__.'/../config/payumoney.php';
        $this->publishes([
            $dist => config_path('payumoney.php'),
        ],'config');

        $this->mergeConfigFrom($dist, 'payumoney');
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {

        $this->app->singleton(Payumoney::class, function($app){
            $config = $app['config']->get('payumoney');

            if(!$config){
                throw new \RuntimeException('missing payumoney configuration section');
            }

            if(empty($config['KEY'])){
                throw new \RuntimeException('missing payumoney configuration: `KEY`');
            }

            if(empty($config['SALT'])){
                throw new \RuntimeException('missing payumoney configuration: `SALT`');
            }

            if(empty($config['DEBUG'])){
                throw new \RuntimeException('missing payumoney configuration: `DEBUG`');
            }

            return new Payumoney($config);
        });

        $this->app->alias(Payumoney::class, 'payumoney-api');
    }

}