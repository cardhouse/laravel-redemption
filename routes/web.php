<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
            'callback' => 'https://laravel.test/twitch/callback',
            'secret' => 'open_sesame'
        ]
    ]);
    return $response;
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

Route::post('/twitch/callback', function (Request $request) {
    return json_decode($request->body())->challenge;
});
