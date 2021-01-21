<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Services\SocialTwitchAccountService;
use App\Models\SocialTwitchAccount;
use Illuminate\Support\Facades\Http;

class SocialLoginTwitchController extends Controller
{
    public function redirect() {
        return Socialite::driver('twitch')->scopes([
            'channel:manage:redemptions',
            'channel:read:redemptions'
        ])->redirect();
    }

    public function return(SocialTwitchAccountService $service) {
        $oauth = Socialite::driver('twitch')->user();
        $user = $service->createOrGetUser($oauth);
        Auth::login($user);
        return redirect('/success');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/twitch/login');
    }

    public function refresh(SocialTwitchAccount $account) {
        $account = Auth::user()->twitch;
        $url = 'https://id.twitch.tv/oauth2/token?grant_type=refresh_token&refresh_token='. $account->refreshToken .'&client_id='. env('TWITCH_CLIENT_ID') .'&client_secret='.env('TWITCH_CLIENT_SECRET');
        $response = Http::post($url, []);
        dd($response->json());
        $account->token = $response->json('access_token');
        $account->save();

        return redirect('/redemptions');
    }
}
