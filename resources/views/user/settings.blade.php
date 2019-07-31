@extends('layouts.user')

@section('page-title') Settings @stop

@section('user_content')

    <div class="mt-3">
        {!! $form !!}
    </div>

    <div class="row delete-account">
        <div class="col-md-4 offset-md-4 border-top text-center">
            {!! $deleteAccountForm !!}
        </div>
    </div>


@stop
