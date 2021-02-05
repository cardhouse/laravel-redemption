<?php

use App\Http\Controllers\SocialLoginTwitchController;
use App\Services\TwitchSubscriptionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

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
    return view('dashboard');
})->middleware('auth');

Route::get('/redemptions', function () {
    $twitch = Auth::user()->twitch;
    // dd($twitch);
    $response = app('twitch')->getRedemption();

    return $response->body();
});

Route::get('/force/{id}', function ($id) {
    Auth::loginUsingId($id, true);
    return Auth::user()->twitch;
});

Route::get('/subscribe/list', function () {
    $response = HTTP::withHeaders([
        'Client-ID' => env('TWITCH_CLIENT_ID'),
        'Authorization' => 'Bearer ' . env('TWITCH_ACCESS_TOKEN'),
        'Content-Type' => 'application/json'
    ])->get('https://api.twitch.tv/helix/eventsub/subscriptions');
    return $response;
});

Route::get('/subscribe/clear/{listener}', function ($listener) {
    $response = HTTP::withHeaders([
        'Client-ID' => env('TWITCH_CLIENT_ID'),
        'Authorization' => 'Bearer ' . env('TWITCH_ACCESS_TOKEN'),
        'Content-Type' => 'application/json'
    ])->get('https://api.twitch.tv/helix/eventsub/subscriptions');


    // foreach (json_decode($response->body())->data as $listener) {
        HTTP::withHeaders([
            'Client-ID' => env('TWITCH_CLIENT_ID'),
            'Authorization' => 'Bearer ' . env('TWITCH_ACCESS_TOKEN'),
            'Content-Type' => 'application/json'
        ])->delete('https://api.twitch.tv/helix/eventsub/subscriptions', [
            'id' => $listener
        ]);
    // }
});

Route::prefix('twitch')->group(function () {
    // OAuth endpoints
    Route::get('/login', [SocialLoginTwitchController::class, 'redirect']);
    Route::get('/oauth/return', [SocialLoginTwitchController::class, 'return']);
    Route::middleware('auth')->group(function () {
        Route::get('/refresh', [SocialLoginTwitchController::class, 'refresh']);
        Route::get('/logout', [SocialLoginTwitchController::class, 'logout']);
    });

    // Subscribe to listeners
    Route::prefix('eventsub')->middleware('auth')->group(function () {
        Route::get('/create', [TwitchSubscriptionService::class, 'subscribe']);
        Route::get('/status', [TwitchSubscriptionService::class, 'status']);
        Route::get('/remove', [TwitchSubscriptionService::class, 'remove']);
    });

    // Redemption endpoints
    Route::prefix('redemptions')->middleware('auth')->group(function () {
        Route::get('/setup', [\App\Http\Controllers\Redemption::class, 'setup']);
        Route::get('/', [\App\Http\Controllers\Redemption::class, 'index']);
        Route::get('/{redemption}', [\App\Http\Controllers\Redemption::class, 'show']);
    });
});

Route::get('/clear', function () {
    Cache::flush();
    abort(204);
});
