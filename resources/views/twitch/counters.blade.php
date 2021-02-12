@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold text-center py-3">Hello {{ $broadcaster->display_name }}</h1>
<p>You have made it to the widget counter section of the site. Good job.</p>
<h2 class="text-lg font-bold underline">Setting up your stream</h2>
<p>In order to get the redemption counter on your stream, copy the URL of any of the links below, and make a Browser Source in OBS for that url.</p>

<ul class="flex">
    @foreach($rewards as $title => $id)
        <div class="w-5 m-2 h-5">
            <a href="https://redemptions.cardhouse.online/count?b={{ $broadcaster->id }}&r={{ $id }}">{{ $title }}</a>
        </div>
    @endforeach
</ul>
<h2 class="text-lg font-bold underline">Size the browser source appropriately</h2>
<p>The size of the browser source in OBS is important. The widget will fill whatever dimensions you feed it, but still be sizable in your OBS...so you are really setting an aspect ratio for the widget itself. I recommend 150px wide by 80px tall</p>
<p>If you make the width of the widget 300px or larger, you will also get text showing the name of the widget between the image and the number in the current stream. Keep in mind, the longer the redemption title, the wider the widget needs to be (if you want the text to appear)</p>
<h2 class="text-lg font-bold underline">Trouble Shooting</h2>
<p>If you do not see your redemption in the links above, make sure you have a Max Number Per Stream set (could be set really really high) because if that is not enabled, I have no way of getting the count.</p>
<p>Make sure to have the "Shutdown source while not active" and "Refresh the browser source" are un-checked. This shouldn't cause a lot of interference if they are selected, but better safe than sorry.</p>
<p>Make sure you have the custom logo images set for the redemption. If you do not have a logo defined (for the middle size specifically), then you will have undesired consequences with your counter not appearing as you would expect.</p>
<p>If you have any further questions or trouble shooting guidance, send me an email at <span>cardhouseonline@gmail.com</span></p>
@endsection