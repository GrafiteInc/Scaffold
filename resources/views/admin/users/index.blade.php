@extends('layouts.admin')

@section('page-title', 'Admin: Users')

@section('admin_content')

    <div class="row">
        <div class="col-md-6 mb-3">
            {!! $index->search('admin.users.search', 'Search Users', '<span class="fas fa-search"></span>', 'get') !!}
        </div>
        <div class="col-md-6 mb-3">
            <div class="btn-toolbar justify-content-end">
                <a class="btn btn-primary" href="{{ route('admin.users.invite') }}">
                    <span class="fas fa-fw fa-envelope"></span>
                    Invite New User
                </a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            @if ($index->items->isEmpty())
                <div class="card card-default text-center">
                    <div class="card-body">
                        <span>No users found.</span>
                    </div>
                </div>
            @else
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h4 class="m-0">Registered Users</h4>
                    </div>
                    <div class="card-body">
                        {!! $index !!}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 mt-4">
            @if ($invites->isEmpty())
                <div class="card card-default text-center">
                    <div class="card-body">
                        <span>No user invites found.</span>
                    </div>
                </div>
            @else
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h4 class="m-0">User Invites</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-borderless m-0 p-0">
                            <tbody>
                                @foreach($invites as $invite)
                                    <tr>
                                        <td>{{ $invite->email }}</td>
                                        <td class="text-right" width="250px">
                                            {!! form()->action('post',
                                                ['invite.resend', $invite],
                                                'Resend Invite',
                                                ['class' => 'btn btn-sm btn-outline-primary mr-2']
                                            ) !!}

                                            {!! form()->confirm('Are you sure you want to revoke this invite?', 'confirmation')
                                                ->action('post',
                                                ['invite.revoke', $invite],
                                                '<span class="fas fa-fw fa-undo"></span> Revoke Invite',
                                                ['class' => 'btn btn-sm btn-outline-warning']
                                            ) !!}
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
