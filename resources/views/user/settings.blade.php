@extends('layouts.user')

@section('page-title') Settings @stop

@section('user_content')

    <div class="mt-3">
        <div class="row">
            <div class="col-md-4 d-flex justify-content-center">
                @if (auth()->user()->avatar)
                    {!! form()
                        ->confirm('Are you sure you want to delete your avatar?', 'confirmation')
                        ->action('delete', 'user.destroy.avatar', '<span class="fas fa-trash"></span>', ['class' => 'btn btn-sm btn-outline-secondary'])
                    !!}
                @endif
                <div class="avatar shadow-sm border" style="background-image: url({{ auth()->user()->avatar_url }})"></div>
            </div>
            <div class="col-md-8">
                <x-fm :content="$form"></x-fm>
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
                    <api-token></api-token>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-8">
        <div class="col-md-4 offset-md-4 border-top text-center">
            <x-fm :content="$deleteAccountForm"></x-fm>
        </div>
    </div>

@stop
