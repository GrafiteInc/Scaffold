@extends('layouts.master')

@section('page-title') Welcome @stop

@section('app-content')

    <div class="container">
        <div class="row mt-4">
            <div class="col-md-6 text-right">
                <a class="btn btn-primary" href="{{ url('login') }}">Login</a>
            </div>
            <div class="col-md-6">
                <a class="btn btn-primary" href="{{ url('register') }}">Regsiter</a>
            </div>
        </div>
    </div>

@stop