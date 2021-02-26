<?php

use Pusher\Pusher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Twitch\Account;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/twitch/callback', [\App\Http\Controllers\EventSubController::class, 'receive'])->middleware(['twitch.validate', 'twitch.unique']);

Route::post('/broadcasting', function (Request $request) {
    $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'));
    return $pusher->socket_auth($request->request->get('channel_name'), $request->request->get('socket_id'));
});

Route::get('/start/{broadcaster}/counter', function(Request $request, $broadcaster) {
    $user = Account::find($broadcaster);

    $redemption = app('twitch')
        ->broadcaster($user)
        ->getReward($request->input('reward_id'))
        ->first();

    return response()->json([
        'name' => Str::plural($redemption['title']),
        'image' => $redemption['image']['url_2x'],
        'color' => $redemption['background_color'],
        'count' => $redemption['redemptions_redeemed_current_stream'] ?: 0
    ]);
});

Route::prefix('clout')->group(function () {
    Route::get('/{term}/add', [App\Http\Controllers\CloutController::class, 'addClout']);
    Route::get('/{term}/subtract', [App\Http\Controllers\CloutController::class, 'removeClout']);
    Route::get('/{term}', [App\Http\Controllers\CloutController::class, 'getClout']);
});
