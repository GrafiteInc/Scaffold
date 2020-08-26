@extends('layouts.master')

@section('page-title', 'Home')

@section('app-content')

    <div class="container">
        <div class="row mt-5">
            <div class="col-md-12 text-center">
                <a class="btn btn-primary m-4" href="{{ url('login') }}">Login</a>
                <a class="btn btn-primary m-4" href="{{ url('register') }}">Register</a>
            </div>
        </div>
    </div>

@stop