<?php

namespace App\Providers;

use App\Mail\Transport\ElasticEmailTransport;
use Illuminate\Mail\MailManager;
use Illuminate\Support\ServiceProvider;

class ElasticEmailServiceProvider extends ServiceProvider
{
    /**
     * Register the Elastic Email driver
     *
     * @return void
     */
    public function register()
    {
        // Register the Elastic Email driver
        $this->app->afterResolving(MailManager::class, function (MailManager $manager) {
            $manager->extend('elastic_email', function ($config) {
                $apiKey = $config['api_key'] ?? env('ELASTIC_EMAIL_API_KEY');
                
                if (!$apiKey) {
                    throw new \Exception('Elastic Email API key is required.');
                }
                
                return new ElasticEmailTransport($apiKey);
            });
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}