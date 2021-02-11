@extends('layouts.app')

@section('content')
    <div>
        <h1>Welcome to cardhouse.online</h1>
        <h2>Redemption Widget</h2>
        <p>
            In order to access the redemption widget, 
            <a href="{{ url('/twitch/redemptions/setup') }}">follow these directions</a>
        </p>
        <p>
            In order to access the counters, 
            <a href="{{ url('/twitch/redemptions') }}">follow these directions</a>
        </p>
    </div>
@endsection