@extends('layouts.app')

@section('page-title', 'Dashboard')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    {!! $form !!}
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3>Uploaded Images</h3>

                    <div class="row">
                        @foreach ($images as $image)
                            <div class="col-md-4 mb-4 overflow-hidden" style="height: 200px;">
                                <img class="w-100 align-top" src="{{ $image }}" alt="">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop