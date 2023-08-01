@extends('layouts.guest')

@section('page-title', 'Register')

@section('content')
    <div class="content-sm mt-4">

        <h3 class="text-center mb-5">Create your account</h3>

        <div class="card mt-5 mb-4 border-0">
            <div class="card-body bg-body-tertiary border-start border-primary bmx-border-3">
                <p class="lead m-0">You've come this far, you're probably still curious about whats inside.</p>
            </div>
        </div>

        <div class="card shadow mb-5">
            <div class="card-body">
                <x-f-base :form="\App\View\Forms\RegistrationForm::class" />
            </div>
        </div>

    </div>

@stop
