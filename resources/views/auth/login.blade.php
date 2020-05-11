@extends('layouts.master')

@section('app-content')

    <div class="form-container mt-4">

        <h2 class="page-title">Please Sign In</h2>

        <form method="POST" action="{{ url('login') }}">
            {!! csrf_field() !!}
            <div class="row">
                <div class="col-md-12">
                    <label>Email</label>
                    <input class="form-control" type="email" name="email" placeholder="Email" value="{{ old('email') }}">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-2">
                    <label>Password</label>
                    <input class="form-control" type="password" name="password" placeholder="Password" id="password">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-2">
                    <label>
                        Remember Me <input type="checkbox" name="remember">
                    </label>
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

