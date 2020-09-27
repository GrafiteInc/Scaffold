@extends('layouts.user')

@section('page-title') Settings @stop

@section('user_content')

    <div class="row mt-2">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <api-token-create></api-token-create>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <api-tokens
                :tokens="{{ $tokens }}"
            ></api-tokens>
        </div>
    </div>

@stop
