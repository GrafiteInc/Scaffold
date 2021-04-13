@extends('layouts.app')

@section('page-title', 'Create a Team')

@section('content')

    <div class="content-md pt-0">
        <div class="row">
            <div class="col-md-12 mb-4">
                <div class="btn-toolbar justify-content-beginning">
                    <a class="btn btn-outline-secondary" href="{{ route('teams') }}">
                        <span class="fas fa-arrow-left"></span> Back to Teams
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        {!! $form !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
