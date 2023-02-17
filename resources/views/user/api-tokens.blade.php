@extends('layouts.user')

@section('page-title', 'API Tokens')

@section('user_content')
    <div class="row mt-2">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <x-f-base
                        :form="\App\View\Forms\TokenForm::class"
                    />
                    @if (session('token'))
                        <div class="row mt-3">
                            <div class="col-md-9">
                                <input type="text" class="form-control " value="{{ session('token') }}">
                            </div>
                            <div class="col-md-3 text-end">
                                <x-html-tag component="\App\View\Components\Global\CopyButton" :data="[session('token')]" />
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-12">
            @if ($tokens->isEmpty())
                <div class="card shadow-sm text-center my-3">
                    <div class="card-body">
                        <span>No tokens listed.</span>
                    </div>
                </div>
            @else
                <table class="table table-carded m-0 p-0">
                    <tbody>
                        @foreach ($tokens as $token)
                            <tr>
                                <td @class([
                                    'text-start' => true,
                                ])>
                                    <a class="btn btn-sm btn-text text-truncate text-start d-inline-block">{{ $token->name }}</a>
                                </td>
                                <td class="d-none d-md-table-cell">
                                    {{ ($token->last_used_at) ? $token->last_used_at->format('M jS, Y h:i A') : 'Not Used' }}
                                </td>
                                <td @class([
                                    'text-start' => true,
                                ])
                                width="500px">
                                    <div class="btn-toolbar justify-content-end">
                                        <x-f-modal
                                            message='<p class="mb-4">Are you sure you want to revoke this API token? It will invalidate any uses of it currently.</p>'
                                            content="Confirm"
                                            method="delete"
                                            :route="['user.destroy-token', $token]"
                                            triggerContent="<span class='fas fa-fw fa-trash'></span>"
                                            triggerClass="btn btn-sm btn-outline-danger"
                                            :payload="['token' => $token->id]"
                                            :options="['class' => 'btn btn-outline-primary float-end']"
                                            :disableOnSubmit=true
                                        ></x-f-modal>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@stop
