<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Services\SocialTwitchAccountService;

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
}
