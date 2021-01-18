@extends('layouts.main')

@section('page-title', $title ?? 'Error')

@section ('app-content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="mt-8">
                    <h1 class="text-huge">@yield('title', __('Error'))</h1>
                    <p class="lead">@yield('message', __('Something clearly didn\'t work, we\'re as suprised as you are.'))</p>
                    <a href="{{ route('dashboard') }}" class="btn btn-primary mt-6">
                        <span class="fas fa-fw fa-home"></span>
                        Home
                    </a>
                </div>
            </div>
            <div class="col-md-6">
                <img class="w-100 mt-8" src="/img/boo.svg" alt="">
            </div>
        </div>
    </div>
@stop

