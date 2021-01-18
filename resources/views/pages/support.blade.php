@extends('layouts.user')

@section('page-title', 'Support')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2 mt-4 mb-4">
                <div class="card card-default shadow-sm">
                    <div class="card-header">
                        <h4 class="m-0">Contact Us</h4>
                    </div>
                    <div class="card-body">
                        <p>We do our best to respond to questions and concerns as quickly as possible.</p>
                        <p>If you'd like to reach out via email you can reach us at:</p>
                        <p class="m-0"><a href="mailto:hello@grafite.ca">hello@grafite.ca</a></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8 offset-md-2 mt-4 mb-4">
                <div class="card card-default shadow-sm">
                    <div class="card-header">
                        <h4 class="m-0">Terms &amp; Policies</h4>
                    </div>
                    <div class="card-body">
                        <p class="m-0">By using this application you agree to the following <a href="{{ route('privacy-policy') }}">Privacy Policy</a> and <a href="{{ route('terms-of-service') }}">Terms of Service</a>.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
