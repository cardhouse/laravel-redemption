<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;

class CloutController extends Controller
{
    public function getClout($term)
    {
        $lowercase = strtolower($term);
        $karma = Redis::get($lowercase);
        $karma = (empty($karma)) ? 'no' : $karma;

        return "$term has $karma clout points";
    }

    public function removeClout($term)
    {
        $lowercase = strtolower($term);
        $karma = Redis::get($lowercase);
        $karma = ($karma == null) ? -1 : $karma - 1;
        
        Redis::set($lowercase, $karma);
    
        return "$term now has $karma clout points";
    }

    public function addClout($term)
    {
        $lowercase = strtolower($term);
        $karma = Redis::get($lowercase);
        $karma = ($karma == null) ? 1 : $karma + 1;
        
        Redis::set($lowercase, $karma);

        return "$term now has $karma clout points";
    }
}
