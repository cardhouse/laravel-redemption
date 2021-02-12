@extends('layouts.app')

@section('content')
    {{-- @if($listener->status() == 409 || $listener->status() == 202) --}}
    <div class="p-5 border-2 shadow-lg rounded-2xl">
    
        <h1 class="text-xl font-bold text-center py-4">
            Setting up the stream source
        </h1>
        <p>In Streamlabs OBS, you will need to create a browser source. Within the source, set the url to <span class="text-blue-600">https://redemptions.cardhouse.online?b={{ $broadcaster->id }}<span></p>
        <p class="mt-4">Set the width of the widget to 420px and the height to 600px</p>
        <p class="mt-4">Make sure to uncheck both the "Shutdown source when not visible" box as well as the "Refresh browser when scene becomes active" box.</p>
    </div>
    {{-- @else
        There was an issue setting up the listener.
        <span>{{ $listener->status() }}</span>
    @endif --}}
    
@endsection