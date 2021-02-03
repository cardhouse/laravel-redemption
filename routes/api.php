<?php

use App\Events\ListenerRevoked;
use App\Events\RedemptionReceived;
use App\Models\Twitch\Redemption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Pusher\Pusher;

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
