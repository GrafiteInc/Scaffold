@extends('layouts.user')

@section('page-title') Settings @stop

@section('user_content')

    <div class="mt-3">
        <div class="row">
            <div class="col-md-4">
                <img class="img-fluid img-thumbnail" src="{{ auth()->user()->avatar_url }}" alt="">
            </div>
            <div class="col-md-8">
                {!! $form !!}
            </div>
        </div>
    </div>

    <div class="row delete-account">
        <div class="col-md-4 offset-md-4 border-top text-center">
            {!! $deleteAccountForm !!}
        </div>
    </div>


@stop
