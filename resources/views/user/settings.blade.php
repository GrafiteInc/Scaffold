@extends('layouts.user')

@section('page-title', 'Settings')

@section('user_content')

    <div class="mt-3">
        <div class="row">
            <div class="col-md-4 d-flex justify-content-center">
                @if (auth()->user()->avatar)
                    <x-f-action
                        confirmMessage="Are you sure you want to delete your avatar?"
                        confirmMethod="confirmation"
                        method="delete"
                        route="user.destroy.avatar"
                        content="<span class='fas fa-trash'></span>"
                        :confirm="true"
                        :payload="['user' => auth()->user()]"
                        :options="['class' => 'btn btn-sm btn-outline-secondary']"
                    ></x-f-action>
                @endif
                <div class="avatar shadow-sm border" style="background-image: url({{ auth()->user()->avatar_url }})"></div>
            </div>
            <div class="col-md-8">
                <x-f :content="$form"></x-f>
            </div>
        </div>
    </div>

    <div class="row mt-8">
        <div class="col-md-4 offset-md-4 border-top text-center">
            <x-f :content="$deleteAccountForm"></x-f>
        </div>
    </div>

@stop
