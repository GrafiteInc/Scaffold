@extends('layouts.app')

@section('page-title', 'Edit Member')

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
                <x-forms.team-member :member="$member" :team="$team"></x-forms.team-member>
            </div>
        </div>
    </div>

@stop
