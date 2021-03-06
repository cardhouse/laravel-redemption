<?php

use App\Events\RedemptionReceived;
use App\Http\Controllers\Redemption;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialLoginTwitchController;
use App\Services\Twitch\Api;
use App\Services\TwitchSubscriptionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->middleware('auth');

Route::get('/redemptions', function () {
    $twitch = Auth::user()->twitch;
    $response = Api::getRedemption($twitch->id, '8ff4cff1-bbc0-4f0b-bc83-f0afd040d7bd');

    return $response->body();
});

Route::get('/force', function () {
    Auth::loginUsingId(2, true);
    return Auth::user()->twitch;
});

Route::get('/subscribe', function () {
    $sub = TwitchSubscriptionService::subscribe('channel.channel_points_custom_reward_redemption.add');

    return $sub;
});

Route::get('/subscribe/list', function () {
    $response = HTTP::withHeaders([
        'Client-ID' => env('TWITCH_CLIENT_ID'),
        'Authorization' => 'Bearer ' . env('TWITCH_ACCESS_TOKEN'),
        'Content-Type' => 'application/json'
    ])->get('https://api.twitch.tv/helix/eventsub/subscriptions');
    return $response;
});

Route::get('/subscribe/clear', function () {
    $response = HTTP::withHeaders([
        'Client-ID' => env('TWITCH_CLIENT_ID'),
        'Authorization' => 'Bearer ' . env('TWITCH_ACCESS_TOKEN'),
        'Content-Type' => 'application/json'
    ])->get('https://api.twitch.tv/helix/eventsub/subscriptions');
    foreach (json_decode($response->body())->data as $listener) {
        HTTP::withHeaders([
            'Client-ID' => env('TWITCH_CLIENT_ID'),
            'Authorization' => 'Bearer ' . env('TWITCH_ACCESS_TOKEN'),
            'Content-Type' => 'application/json'
        ])->delete('https://api.twitch.tv/helix/eventsub/subscriptions', [
            'id' => $listener->id
        ]);
    }
});

Route::prefix('twitch')->group(function () {
    Route::get('/login', [SocialLoginTwitchController::class, 'redirect']);
    Route::get('/oauth/return', [SocialLoginTwitchController::class, 'return']);
    Route::get('/oauth/refresh', [SocialLoginTwitchController::class, 'refresh'])->middleware('auth');
    Route::get('/logout', [SocialLoginTwitchController::class, 'logout'])->middleware('auth');
});

Route::get('/clear', function () {
    Cache::flush();
    abort(204);
});
