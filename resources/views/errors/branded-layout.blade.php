@extends('layouts.app')

@section('page-title', $title ?? 'Error')

@section ('app-content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="mt-8">
                    <h1 class="text-huge">@yield('code', __('Oh no'))</h1>
                    <p class="lead">@yield('message', __('Something clearly didn\'t work, we\'re as suprised as you are.'))</p>
                </div>
            </div>
            <div class="col-md-6">
                <img class="w-100 mt-8" src="/img/boo.svg" alt="">
            </div>
        </div>
    </div>
@stop

