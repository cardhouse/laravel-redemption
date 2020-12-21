<?php

use App\Events\RedemptionReceived;
use App\Http\Controllers\Redemption;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialLoginTwitchController;
use Illuminate\Support\Facades\Auth;

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
});

Route::get('/redemptions', function () {
    $response = HTTP::withHeaders([
        'Client-ID' => env('TWITCH_CLIENT_ID'),
        'Authorization' => 'Bearer ' . Auth::user()->twitch->token,
        'Content-Type' => 'application/json'
    ])->get('https://api.twitch.tv/helix/channel_points/custom_rewards', [
        'broadcaster_id' =>  Auth::user()->twitch->id
    ]);

    return $response->json();
});

Route::get('/redeem', function () {
    RedemptionReceived::dispatch(new Redemption);
});

Route::get('/subscribe', function () {
    $response = HTTP::withHeaders([
        'Client-ID' => env('TWITCH_CLIENT_ID'),
        'Authorization' => 'Bearer ' . env('TWITCH_ACCESS_TOKEN'),
        'Content-Type' => 'application/json'
    ])->post('https://api.twitch.tv/helix/eventsub/subscriptions', [
        'type' => 'channel.channel_points_custom_reward_redemption.add',
        'version' => 1,
        'condition' => [
            'broadcaster_user_id' => '548965051'
        ],
        'transport' => [
            'method' => 'webhook',
            'callback' => 'https://91c637230add.ngrok.io/api/twitch/callback',
            'secret' => 'open_sesame'
        ]
    ]);
    return redirect('/redemptions');
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
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia\Inertia::render('Dashboard');
})->name('dashboard');
