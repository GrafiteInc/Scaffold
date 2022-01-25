@extends('layouts.guest')

@section('page-title', 'Login')

@section('content')
    <div class="content-sm mt-4 mb-4">

        <h3 class="text-center">Sign into your account</h3>

        @if (Route::has('register'))
            <p class="text-center">Or <a href="{{ route('register') }}">create a new account</a></p>
        @endif

        <div class="card mt-5 mb-4 border-0 bmx-tada">
            <div class="card-body bg-dark border-start bmx-border-purple bmx-border-3">
                <p class="lead m-0">After all it's way more fun on the inside!</p>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-body">
                <x-forms.login></x-forms.login>
            </div>
        </div>

    </div>

@stop

