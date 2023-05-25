@extends('layouts.user')

@section('page-title', 'Change Password')

@section('user_content')

    <div class="row mt-3">
        <div class="col-md-4">
            <div class="row">
                <div class="col-md-8">
                    @if (auth()->user()->avatar)
                        <div class="mb-4 d-flex justify-content-center">
                            <div class="avatar shadow-sm border" style="background-image: url({{ auth()->user()->avatar_url }})"></div>
                        </div>
                    @endif
                    <a class="btn w-100 btn-outline-secondary mb-3" href="{{ route('user.settings') }}">Main Settings</a>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <x-f-base :form="\App\View\Forms\UserPasswordForm::class" />
        </div>
    </div>

@stop
