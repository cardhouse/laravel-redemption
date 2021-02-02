<?php

namespace App\Models\Twitch;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Account extends Model
{
    use HasFactory;

    protected $guarded = ['created_at'];

    protected $table = 'social_twitch_accounts';

    public function user() {
        return $this->belongsTo('\App\Models\User');
    }

    public function refresh() {
        $query = http_build_query([
            'grant_type' => 'refresh_token',
            'refresh_token' => $this->refreshToken,
            'client_id' => env('TWITCH_CLIENT_ID'),
            'client_secret' => env('TWITCH_CLIENT_SECRET')
        ]);
        $url = 'https://id.twitch.tv/oauth2/token' . $query;
        $response = Http::post($url, []);
        Log::info("Response received from twitch", ['response' => $response]);
        $this->token = $response->json('access_token');
        $this->save();

        return $this->fresh();
    }
}
