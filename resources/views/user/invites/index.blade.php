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
                <table class="table table-striped">
                    <thead>
                        <th>Message</th>
                        <th width="215px" class="text-right">Actions</th>
                    </thead>
                    <tbody>
                        @foreach($invites as $invite)
                            <tr>
                                <td>{{ $invite->message }}</td>
                                <td>
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
            @endif
        </div>
    </div>

@stop