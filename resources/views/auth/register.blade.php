@extends('layouts.master')

@section('page-title') Register @stop

@section('app-content')

    <div class="content-sm mt-4">

        <h3 class="text-center mb-5">Create your account</h3>

        <div class="card mt-5 mb-4 border-0">
            <div class="card-body bg-light border-left border-primary">
                <p class="lead m-0">You've come this far, you're probably still curious about whats inside.</p>
            </div>
        </div>

        <form method="POST" action="{{ url('register') }}">
            @honeypot
            {!! csrf_field() !!}
            <div class="row">
                <div class="col-md-12 mt-2">
                    <label>Name</label>
                    <input class="form-control" type="text" name="name" value="{{ old('name') }}" required placeholder="Name">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-2">
                    <label>Email</label>
                    <input class="form-control" type="email" name="email" value="{{ old('email') }}" required placeholder="Email">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-2">
                    <label>Password</label>
                    <input class="form-control" type="password" name="password" required placeholder="Password">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-2">
                    <label>Confirm Password</label>
                    <input class="form-control" type="password" name="password_confirmation" required placeholder="Password Confirmation">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-4">
                    <div class="btn-toolbar justify-content-between">
                        <a class="btn btn-secondary" href="{{ route('login') }}">Login</a>
                        <button class="btn btn-primary" type="submit">Register</button>
                    </div>
                </div>
            </div>
        </form>

    </div>

@stop
