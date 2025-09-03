<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Mail\MailManager;
use App\Mail\CustomResendTransport;

class ResendServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->extend('mail.manager', function (MailManager $manager, $app) {
            $manager->extend('resend', function ($config) {
                return new CustomResendTransport(config('resend.api_key'));
            });
            
            return $manager;
        });
    }
}