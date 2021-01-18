@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-md-12">
            @include('user.nav')
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            @yield('user_content')
        </div>
    </div>

@stop
