@extends('layouts.app-with-sidebar-panel')

@section('page-title', 'Dashboard')

@section('panel')
    @if (auth()->user()->hasRole('admin'))
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header text-center bmx-bg-indigo-light">
                        <h4 class="m-0">New User</h4>
                    </div>
                    <x-forms.user-wizard></x-forms.user-wizard>
                </div>
            </div>
        </div>
    @endif

    <x-forms.image-upload></x-forms.image-upload>

    <copy-button message="Ok so we have Vue!">Test Button</copy-button>

    <div class="mt-5">
        <x-html-tilt glare="true">
            <img src="https://placehold.co/400x600" alt="">
        </x-html-tilt>
    </div>

    {{-- <x-html-hovercard content="Testing">
        <img src="https://placehold.co/600x400" alt="">
        <h4>Test Ideas</h4>
        <x-html-rating value="6" max="12" />
    </x-html-hovercard> --}}

@stop

@section('content')
    {{-- <x-html-announcement text="This is a general statement for all our users!" dismiss background="danger" /> --}}
    {{-- <x-html-animation component="pulse" /> --}}

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row">
                        @foreach ($images as $index => $image)
                            <div class="col-md-4 mb-4 overflow-hidden bmx-pointer" style="height: 200px;">
                            {{-- <x-html-offcanvas-image :title="$image" :src="$image" cssClass="w-100 img-fluid" position="end">
                                {!! app(\Grafite\Html\Tags\Image::class)->placeholder()->fluid()->source(url($image))->render() !!}
                            </x-html-offcanvas-image> --}}
                            <x-html-offcanvas :title="$image" cssClass="w-100 img-fluid" position="end">
                                {!! app(\Grafite\Html\Tags\Image::class)->placeholder()->fluid()->source(url($image))->render() !!}
                            </x-html-offcanvas>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
{!! app(\Grafite\Html\Tags\Map::class)
                ->zoom(3)
                ->maxZoom(20)
                ->bubbles([
                    [
                        'x' => 43.981739,
                        'y' => -80.735542,
                        'opacity' => 0.8,
                        'radius' => 400,
                        'tooltip' => 'I\'m a bubble.',
                        'click' => 'window.app.pending()'
                    ]
                ])
                ->skin('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png')
                ->center(43.981739, -80.735542)
                ->marker(43.981739, -80.735542, 'This is my home')
                ->render() !!}
                </div>
            </div>
        </div>
        {{-- <div class="col-md-12">
            {!! app(\App\View\Charts\WordyChart::class)->html() !!}
        </div> --}}
        <div class="col-md-12">
            {!! $chart !!}
        </div>
    </div>
@stop