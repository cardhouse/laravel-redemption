@extends('layouts.app')

@section('content')
<h1 class="text-2xl text-bold text-center py-3">Hello {{ $broadcaster->display_name }}</h1>
<p>You have made it to the widget counter section of the site. Good job.</p>
<p>In order to get the redemption counter on your stream, copy the URL of any of the links below, and make a Browser Source in OBS for that url.</p>

<ul class="flex flex-col">
    @foreach($rewards as $title => $id)
        <a href="https://redemptions.cardhouse.online/count?b={{ $broadcaster->id }}&r={{ $id }}">{{ $title }}</a>
    @endforeach
</ul>

<p>The size of the browser source in OBS is important. The widget will fit the dimensions you feed it, but still be sizable in your OBS...so you are really setting an aspect ratio for the widget itself. I recommend 150px x 80px</p>
<p>If you make the width of the widget 300px or larger, you will also get text showing the name of the widget between the image and the number in the current stream.</p>
<p>If you do not see your redemption in the links above, make sure you have a Max Number Per Stream set (could be set really really high) because if that is not enabled, I have no way of getting the count.</p>
@endsection