<?php

namespace App\Services;

use App\Models\User;
use App\Models\SocialTwitchAccount as TwitchAccount;
use Laravel\Socialite\Contracts\User as OauthUser;

class SocialTwitchAccountService
{
    public function createOrGetUser(OauthUser $oauth)
    {
        $account = TwitchAccount::find($oauth->getId());

        if($account) {
            return $account->user;
        }

        $account = new TwitchAccount($oauth->user);
        $account->token = $oauth->token;
        $account->refreshToken = $oauth->refreshToken;

        $user = User::whereEmail($oauth->getEmail())->first();

        if(!$user) {
            $user = User::create([
                'email' => $oauth->getEmail(),
                'name' => $oauth->getName(),
                'password' => md5(rand(1,10000)),
            ]);
        }

        $account->user()->associate($user);
        $account->save();
        
        return $user;
    }

}