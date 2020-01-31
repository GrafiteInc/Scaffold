@extends('errors::branded-layout')

@section('code', $code)
@section('title', __('Server Error'))

@section('image')
<div style="background-image: url({{ asset('/svg/500.svg') }});" class="absolute pin bg-cover bg-no-repeat md:bg-left lg:bg-center">
</div>
@endsection

@section('message', __($message))