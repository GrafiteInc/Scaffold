@extends('layouts.guest')

@section('page-title', 'Home')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <div class="card mt-5">
                    <div class="card-body">
                        <span>Make sure you set <code>SESSION_DOMAIN</code> and <code>SANCTUM_STATEFUL_DOMAINS</code> with your local domain for sessions to function correctly.</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-12 text-center">
                <a class="btn btn-primary m-4" href="{{ url('login') }}">Login</a>
                <a class="btn btn-primary m-4" href="{{ url('register') }}">Register</a>
            </div>
        </div>
    </div>

@stop