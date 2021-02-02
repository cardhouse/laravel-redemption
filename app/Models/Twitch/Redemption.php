<?php

namespace App\Models\Twitch;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Services\TwitchSubscriptionService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class Redemption extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getChannel()
    {
        return 'redemptions.' . $this->broadcaster_user_id;
    }

    public function getProfilePic()
    {
        $response = Http::withHeaders(TwitchSubscriptionService::getHeaders())
            ->get('https://api.twitch.tv/helix/users', [
                'id' => $this->user_id
            ]);

        return $response->json('data.0.profile_image_url');
    }
}
