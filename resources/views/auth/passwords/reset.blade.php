@extends('layouts.master')

@section('page-title') Password Reset @stop

@section('app-content')

    <div class="content-sm mt-4">

        <h3 class="text-center mb-5">Password Reset</h3>

        <form method="POST" action="{{ route('password.reset', $token) }}">
            {!! csrf_field() !!}
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="row">
                <div class="col-md-12 mt-2">
                    <label>Email</label>
                    <input class="form-control" type="email" name="email" value="{{ old('email') }}">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-2">
                    <label>Password</label>
                    <input class="form-control" type="password" name="password">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-2">
                    <label>Confirm Password</label>
                    <input class="form-control" type="password" name="password_confirmation">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-4">
                    <button class="btn btn-primary" type="submit">Reset Password</button>
                </div>
            </div>
        </form>
    </div>

@stop
