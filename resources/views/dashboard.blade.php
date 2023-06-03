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
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row">
                        @foreach ($images as $index => $image)
                            <div class="col-md-4 mb-4 overflow-hidden bmx-pointer" onclick="window.offCanvas('Image #{{ $index }}', '{{ $image }}')" style="height: 200px;">
                                <img loading="lazy" class="w-100 align-top" src="{{ $image }}" alt="">
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
                        'click' => 'window.pending()'
                    ]
                ])
                ->skin('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png')
                ->center(43.981739, -80.735542)
                ->marker(43.981739, -80.735542, 'This is my home')
                ->render() !!}
                </div>
            </div>
        </div>
    </div>
@stop