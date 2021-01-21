<?php

namespace App\Models\Twitch;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Redemption extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getChannel()
    {
        return 'redemptions.' . $this->broadcaster_user_id;
    }
}
