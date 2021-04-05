@extends('layouts.app')

@section('page-title', "Edit {$team->name}")

@section('content')

    @include('teams.nav')

    <div class="row">
        <div class="col-md-4">
            <div class="mb-4 d-flex justify-content-center">
                <div class="avatar shadow-sm border bmx-scale-1-hover bmx-drop-shadow-hover" style="background-image: url({{ $team->avatar_url }})"></div>
            </div>
            @if ($team->avatar)
                <div class="d-flex justify-content-center">
                    <x-f-modal
                        message='<p class="mb-4">Are you sure you want to delete this avatar?</p>'
                        content="Confirm"
                        method="delete"
                        route="team.destroy.avatar"
                        triggerContent="<span class='fas fa-fw fa-trash'></span> Delete Avatar"
                        triggerClass="btn btn-block w-50 btn-outline-danger mb-3"
                        :payload="['team' => $team->id]"
                        :options="['class' => 'btn btn-outline-primary float-right']"
                    ></x-f-modal>
                </div>
            @endif
        </div>
        <div class="col-md-8 mb-4">
            <div>
                {!! $form !!}
            </div>
        </div>
    </div>
@stop
