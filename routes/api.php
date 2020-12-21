<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

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
        
        default:
            return response('', 200);
            break;
    }
})->middleware('validate.twitch');

// Route::post('/twitch/callback', function (Request $request) {
//     return $request->get('challenge');
// });
