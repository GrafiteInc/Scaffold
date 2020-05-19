@extends('layouts.master')

@section('page-title') Register @stop

@section('app-content')

    <div class="content-sm mt-4">

        <h3 class="text-center mb-5">Register</h3>

        <form method="POST" action="{{ url('register') }}">
            {!! csrf_field() !!}
            <div class="row">
                <div class="col-md-12 mt-2">
                    <label>Name</label>
                    <input class="form-control" type="text" name="name" value="{{ old('name') }}" placeholder="Name">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-2">
                    <label>Email</label>
                    <input class="form-control" type="email" name="email" value="{{ old('email') }}" placeholder="Email">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-2">
                    <label>Password</label>
                    <input class="form-control" type="password" name="password" placeholder="Password">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-2">
                    <label>Confirm Password</label>
                    <input class="form-control" type="password" name="password_confirmation" placeholder="Password Confirmation">
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