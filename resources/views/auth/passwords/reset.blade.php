@extends('layouts.guest')

@section('page-title', 'Password Reset')

@section('content')

    <div class="content-sm mt-4">

        <h3 class="text-center mb-5">Password Reset</h3>

        <div class="card mt-5 mb-4 border-0">
            <div class="card-body bg-body-tertiary border-start border-primary bmx-border-3">
                <p class="lead m-0">New passwords are better anyways.</p>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('password.update') }}">
                    {!! csrf_field() !!}
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="row">
                        <div class="col-md-12 mt-2">
                            <label class="form-label">Email</label>
                            <input class="form-control" type="email" name="email" value="{{ old('email') }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mt-2">
                            <label class="form-label">Password</label>
                            <input class="form-control" type="password" name="password">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mt-2">
                            <label class="form-label">Confirm Password</label>
                            <input class="form-control" type="password" name="password_confirmation">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mt-4">
                            <button class="btn btn-primary float-end" type="submit">Reset Password</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@stop
