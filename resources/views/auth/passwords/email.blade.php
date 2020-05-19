@extends('layouts.master')

@section('page-title') Forgot Password @stop

@section('app-content')

    <div class="content-sm mt-4">

        <h3 class="text-center mb-5">Forgot Password</h3>

        <form method="POST" action="{{ route('password.email') }}">
            {!! csrf_field() !!}

            <div class="row">
                <div class="col-md-12 mt-2">
                    <label>Email</label>
                    <input class="form-control" type="email" name="email" required placeholder="Email" value="{{ old('email') }}">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-4 btn-toolbar justify-content-between">
                    <a class="btn btn-secondary" href="{{ route('login') }}">Wait I remember!</a>
                    <button class="btn btn-primary" type="submit" class="button">Send Reset Link</button>
                </div>
            </div>
        </form>

    </div>

@stop

@section('alerts')
    @if (session('status'))
        <div class="alert alert-info" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            {{ __('A fresh password reset link has been sent to your email address.') }}
        </div>
    @endif
@stop
