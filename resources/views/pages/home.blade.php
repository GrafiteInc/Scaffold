@extends('layouts.master')

@section('page-title') Home Page @stop

@section('app-content')

    <div class="container">
        <div class="row mt-5">
            <div class="col-md-6 text-right">
                <a class="btn btn-primary" href="{{ url('login') }}">Login</a>
            </div>
            <div class="col-md-6">
                <a class="btn btn-primary" href="{{ url('register') }}">Register</a>
            </div>
        </div>
    </div>

@stop