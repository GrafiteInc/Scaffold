@extends('layouts.guest')

@section('page-title', 'Login')

@section('content')
    <div class="content-sm mt-4 mb-4">
        <h3 class="text-center">Sign into your account</h3>

        @if (Route::has('register'))
            <p class="text-center">Or <a href="{{ route('register') }}">create a new account</a></p>
        @endif

        <div class="card mt-5 mb-4 border-0 bmx-tada">
            <div class="card-body bg-body-tertiary border-start bmx-border-purple bmx-border-3">
                <p class="lead m-0">After all it's way more fun on the inside!</p>
            </div>
        </div>

        <div class="card shadow border-0">
            <div class="card-body">
                <x-f-base :form="\App\View\Forms\LoginForm::class" />

                <x-html-divider text="or sign in with" />

                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('auth.social.redirect', ['provider' => 'google']) }}" class="btn btn-outline-dark">
                        <i class="fa fa-google me-2"></i> Google
                    </a>
                    <a href="{{ route('auth.social.redirect', ['provider' => 'facebook']) }}" class="btn btn-outline-dark">
                        <i class="fa fa-facebook me-2"></i> Facebook
                    </a>
                    <a href="{{ route('auth.social.redirect', ['provider' => 'linkedin-openid']) }}" class="btn btn-outline-dark">
                        <i class="fa fa-linkedin me-2"></i> LinkedIn
                    </a>
                    <a href="{{ route('auth.social.redirect', ['provider' => 'github']) }}" class="btn btn-outline-dark">
                        <i class="fa fa-github me-2"></i> GitHub
                    </a>
                </div>
            </div>
        </div>
    </div>
@stop

