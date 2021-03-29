@extends('layouts.guest')

@section('page-title', 'Login')

@section('content')
    <div class="content-sm mt-4">

        <h3 class="text-center">Sign into your account</h3>
        @if (Route::has('register'))
            <p class="text-center">Or <a href="{{ route('register') }}">create a new account</a></p>
        @endif

        <div class="card mt-5 mb-4 border-0 bmx-tada">
            <div class="card-body bg-light border-left border-primary bmx-border-3">
                <p class="lead m-0">After all it's way more fun on the inside!</p>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-body">
                <form method="POST" action="{{ url('login') }}">
                    @honeypot
                    {!! csrf_field() !!}
                    <div class="row">
                        <div class="col-md-12">
                            <label>Email</label>
                            <input class="form-control" type="email" required name="email" value="{{ old('email') }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mt-2">
                            <label>Password</label>
                            <input class="form-control" type="password" required name="password" id="password">
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6 mt-2">
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label" for="remember">Remember Me </label>
                            </div>
                        </div>
                        <div class="col-md-6 text-right">
                            <a class="d-block mt-3" href="{{ route('password.request') }}">Forgot Password?</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mt-4">
                            <div class="btn-toolbar justify-content-end">
                                <button class="btn btn-block btn-primary" type="submit">Login</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>

@stop

