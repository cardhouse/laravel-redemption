<?php

namespace App\Providers;

use App\Services\Twitch\Api;
use Illuminate\Support\ServiceProvider;

class TwitchServiceProvider extends ServiceProvider
{
    /**
     * Register Twitch API.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('twitch', function () {
            return new Api();
        });
    }
}
