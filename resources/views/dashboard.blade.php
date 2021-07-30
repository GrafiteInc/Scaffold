@extends('layouts.app')

@section('page-title', 'Dashboard')

@section('content')

    @if (auth()->user()->hasRole('admin'))
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header text-center">
                        <h4 class="m-0">New User</h4>
                    </div>
                    {!! $wizard !!}
                </div>
            </div>
        </div>
    @endif

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    {!! $form !!}

                    <h3 class="mt-5">Uploaded Images</h3>

                    <div class="row">
                        @foreach ($images as $image)
                            <div class="col-md-4 mb-4 overflow-hidden" style="height: 200px;">
                                <img loading="lazy" class="w-100 align-top" src="{{ $image }}" alt="">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop