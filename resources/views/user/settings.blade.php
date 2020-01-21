@extends('layouts.user')

@section('page-title') Settings @stop

@section('user_content')

    <div class="mt-3">
        <div class="row">
            <div class="col-md-4 d-flex justify-content-center">
                @if (auth()->user()->avatar)
                    <div class="delete-avatar-btn">
                        {!! form()
                            ->confirm('Are you sure you want to delete your avatar?', 'confirmation')
                            ->action('delete', 'user.destroy.avatar', 'delete', ['class' => 'btn btn-sm btn-outline-secondary'])
                        !!}
                    </div>
                @endif
                <div class="user-avatar shadow-sm border" style="background-image: url({{ auth()->user()->avatar_url }})"></div>
            </div>
            <div class="col-md-8">
                {!! $form !!}
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="m-0">API Token</h5>
                </div>
                <div class="card-body">
                    <api-token
                        :token="'{{ auth()->user()->api_token }}'"
                    ></api-token>
                </div>
            </div>
        </div>
    </div>

    <div class="row delete-account">
        <div class="col-md-4 offset-md-4 border-top text-center">
            {!! $deleteAccountForm !!}
        </div>
    </div>

@stop
