@extends('layouts.app')

@section('content')
    <div class="container flex flex-col">
        <h1 class="text-center text-xl font-bold py-4">
            Welcome to cardhouse.online
        </h1>
        <section class="border-2 rounded-md p-4 mb-3">
            <h2>Redemption Widget</h2>
            <p>
                In order to access the redemption widget, 
                <a class="text-blue-400 underline" href="{{ url('/twitch/redemptions/setup') }}">follow these directions</a>
            </p>
        
        </section>
        <section class="border-2 rounded-md p-4 mb-3">
        <h2>Redemption Counters</h2>
            <p>
                In order to access the counters, 
                <a class="text-blue-400 underline" href="{{ url('/twitch/redemptions') }}">follow these directions</a>
            </p>
        </section>
    </div>
@endsection