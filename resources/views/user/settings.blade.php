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
                                <div class="avatar shadow-sm border bmx-scale-1-hover bmx-drop-shadow-hover" style="background-image: url({{ auth()->user()->avatar_url }})"></div>
                            </div>
                            <x-f-modal
                                message='<p class="mb-4">Are you sure you want to delete your avatar?</p>'
                                content="Confirm"
                                method="delete"
                                route="user.destroy.avatar"
                                triggerContent="<span class='fas fa-fw fa-trash'></span> Delete Avatar"
                                triggerClass="btn btn-block btn-outline-danger mb-3"
                                :payload="['user' => auth()->id()]"
                                :options="['class' => 'btn btn-outline-primary float-right']"
                            ></x-f-modal>
                        @endif
                        {!! $logoutForm !!}
                        <a class="btn btn-block btn-outline-secondary mb-3" href="{{ route('user.settings.password') }}">
                            <span class="fas fa-fw fa-lock"></span>
                            Change Password
                        </a>
                        @if (auth()->user()->usesTwoFactor('authenticator'))
                            <a class="btn btn-block btn-outline-secondary mb-3" href="{{ route('user.settings.two-factor') }}">
                                <span class="fas fa-fw fa-shield-alt"></span>
                                Two Factor Auth
                            </a>
                        @endif
                        <hr class="bmx-mt-6 mb-4">
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
