@extends('layouts.user')

@section('page-title', 'Settings')

@section('user_content')

    <div class="mt-3">
        <div class="row">
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-8">
                        @if (auth()->user()->avatar)
                            <div class="mb-4 d-flex justify-content-center">
                                <div class="avatar shadow-sm border" style="background-image: url({{ auth()->user()->avatar_url }})"></div>
                            </div>
                            <x-f-action
                                confirmMessage="Are you sure you want to delete your avatar?"
                                confirmMethod="confirmation"
                                method="delete"
                                route="user.destroy.avatar"
                                content="<span class='fas fa-trash'></span> Delete Avatar"
                                :confirm="true"
                                :payload="['user' => auth()->id()]"
                                :options="['class' => 'btn btn-block btn-outline-danger mb-3']"
                            ></x-f-action>
                        @endif
                        <a class="btn btn-block btn-outline-secondary mb-3" href="{{ route('user.settings.password') }}">Change Password</a>
                        <hr class="mt-6 mb-4">
                        <x-f :content="$deleteAccountForm"></x-f>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <x-f :content="$form"></x-f>
            </div>
        </div>
    </div>

@stop
