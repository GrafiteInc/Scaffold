@extends('layouts.master')

@section('page-title') Login @stop

@section('app-content')

    <div class="content-sm mt-4">

        <h3 class="text-center mb-5">Please Sign In</h3>

        <form method="POST" action="{{ url('login') }}">
            @honeypot
            {!! csrf_field() !!}
            <div class="row">
                <div class="col-md-12">
                    <label>Email</label>
                    <input class="form-control" type="email" required name="email" placeholder="Email" value="{{ old('email') }}">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-2">
                    <label>Password</label>
                    <input class="form-control" type="password" required name="password" placeholder="Password" id="password">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-2">
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember">Remember Me </label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-4">
                    <div class="btn-toolbar justify-content-between">
                        <a class="btn btn-secondary" href="{{ route('password.request') }}">Forgot Password</a>
                        <button class="btn btn-primary" type="submit">Login</button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-2">
                    <a class="btn btn-block btn-info" href="{{ route('register') }}">Register</a>
                </div>
            </div>
        </form>

    </div>

@stop

