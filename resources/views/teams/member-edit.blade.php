@extends('layouts.dashboard')

@section('page-title') Teams: Edit Member @stop

@section('content')

    <div class="row mt-2">
        <div class="col-md-12 text-left">
            <a href="{{ $teamLink }}" class="btn btn-sm btn-outline-secondary">
                <span class="fas fa-arrow-left"></span>
                Return to Team
            </a>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-12 mb-4">
            <div>
                {!! $form !!}
            </div>
        </div>
    </div>

@stop
