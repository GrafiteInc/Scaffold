@extends('layouts.app')

@section('page-title', "Teams: Edit {$team->name}")

@section('content')

    @include('teams.nav')

    <div class="row">
        <div class="col-md-4 d-flex justify-content-center">
            @if (auth()->user()->avatar)
                <x-f-action
                    confirmMessage="Are you sure you want to delete this team avatar?"
                    confirmMethod="confirmation"
                    method="delete"
                    route="team.destroy.avatar"
                    content="<span class='fas fa-trash'></span>"
                    :confirm="true"
                    :payload="['team' => $team->id]"
                    :options="['class' => 'btn btn-sm btn-outline-secondary']"
                ></x-f-action>
            @endif
            <div class="avatar shadow-sm border" style="background-image: url({{ $team->avatar_url }})"></div>
        </div>
        <div class="col-md-8 mb-4">
            <div>
                {!! $form !!}
            </div>
        </div>
    </div>
@stop
