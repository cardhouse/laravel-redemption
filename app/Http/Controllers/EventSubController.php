<?php

namespace App\Http\Controllers;

use App\Events\CountUpdated;
use App\Events\ListenerRevoked;
use App\Events\RedemptionReceived;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use App\Models\Twitch\Account;
use Illuminate\Http\Request;
use App\Models\Redemption;

class EventSubController extends Controller
{
    public function receive(Request $request)
    {
        switch ($request->header('Twitch-Eventsub-Message-Type')) {
            case 'webhook_callback_verification':
                return $request->input('challenge');
                break;
            case 'revocation':
                ListenerRevoked::dispatch($request->json('subscription.id'));
                abort(204);
            case 'notification':
                $broadcaster = $this->getBroadcaster($request->json('event.broadcaster_user_id'));
                $reward = app('twitch')
                    ->broadcaster($broadcaster)
                    ->getRedemption($request->json('event.reward.id'));

                if (
                    $reward->count() == 1
                    && !$reward->first()['should_redemptions_skip_request_queue']
                ) {
                    $redemption = Redemption::make($request->json('event'));
                    $redemption->event_id = $request->json('event.id');
                    $redemption->image = $redemption->getProfilePic();
                    Cache::put($request->json('event.id'), $redemption, 86400);
                    RedemptionReceived::dispatch($redemption);
                    CountUpdated::dispatch($redemption, $reward->first()['redemptions_redeemed_current_stream']);
                }
                return response('', 200);
            default:
                return response('', 200);
                break;
        }
    }

    private function getBroadcaster($id)
    {
        return Account::find($id);
    }
}
