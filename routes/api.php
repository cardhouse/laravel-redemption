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

Route::post('/twitch/callback', function (Request $request) {
    switch ($request->header('Twitch-Eventsub-Message-Type')) {
        case 'webhook_callback_verification':
            return $request->input('challenge');
            break;
        case 'notification':
            if (Cache::has($request->json('event.id'))) {
                abort(204);
            }
            $redemption = Redemption::make($request->json('event'));
            $redemption->event_id = $request->json('event.id');
            Cache::put($request->json('event.id'), $redemption, 86400);
            RedemptionReceived::dispatch($redemption);
            return response('', 200);
        case 'revocation':
            ListenerRevoked::dispatch($request->json('subscription.id'));
            abort(204);
        default:
            return response('', 200);
            break;
    }
    // });
})->middleware('validate.twitch');

Route::post('/broadcasting', function (Request $request) {
    $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'));
    return $pusher->socket_auth($request->request->get('channel_name'), $request->request->get('socket_id'));
});
