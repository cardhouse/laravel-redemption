<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\SocialTwitchAccountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

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
        return redirect('/');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function refresh() {
        return Auth::user()->twitch->refresh();
    }
}
