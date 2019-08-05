@extends('layouts.user')

@section('page-title') Invites @stop

@section('user_content')

    <div class="row">
        <div class="col-md-12 mt-3">
            @if ($invites->isEmpty())
                <div class="card card-default text-center shadow-sm">
                    <div class="card-body">
                        <span>No invites found.</span>
                    </div>
                </div>
            @else
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h4 class="m-0">Available Invites</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless m-0 p-0">
                            <tbody>
                                @foreach($invites as $invite)
                                    <tr>
                                        <td>{{ $invite->message }}</td>
                                        <td width="205px">
                                            <div class="btn-toolbar justify-content-between">
                                                {!! form()->action('post',
                                                    ['user.invites.accept', $invite],
                                                    'Accept Invite',
                                                    ['class' => 'btn btn-sm btn-outline-primary']
                                                ) !!}

                                                {!! form()->action('post',
                                                    ['user.invites.reject', $invite],
                                                    'Reject Invite',
                                                    ['class' => 'btn btn-sm btn-outline-warning']
                                                ) !!}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>

@stop