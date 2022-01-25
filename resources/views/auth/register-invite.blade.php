@extends('layouts.guest')

@section('page-title', 'Register')

@section('content')
    <div class="content-sm mt-4">

        <h3 class="text-center mb-5">Invite Registration</h3>

        <div class="card mt-5 mb-4 border-0">
            <div class="card-body bg-dark border-start border-primary bmx-border-3">
                <p class="lead m-0">You should probably thank the person who invited you.</p>
            </div>
        </div>

        <div class="card shadow mb-5">
            <div class="card-body">
                <form method="POST" action="{{ route('register.invite') }}">
                    {!! csrf_field() !!}

                    <input name="activation_token" type="hidden" value="{{ request('activate_token') }}">

                    <div class="row">
                        <div class="col-md-12">
                            <label class="form-label">Email</label>
                            <input class="form-control" disabled type="email" name="email_facade" value="{!! request('email') !!}" placeholder="Email">
                            <input type="hidden" name="email" value="{!! request('email') !!}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mt-2">
                            <label class="form-label">Name</label>
                            <input class="form-control" type="text" name="name" value="{{ old('name') }}" placeholder="Name">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mt-2">
                            <label class="form-label">Password</label>
                            <input class="form-control" type="password" name="password" placeholder="Password">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mt-2">
                            <label class="form-label">Confirm Password</label>
                            <input class="form-control" type="password" name="password_confirmation" placeholder="Password Confirmation">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mt-4">
                            <div class="btn-toolbar justify-content-end">
                                <button class="btn btn-primary" type="submit">Register</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>

@stop