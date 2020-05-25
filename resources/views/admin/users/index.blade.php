@extends('layouts.admin')

@section('page-title') Admin: Users @stop

@section('admin_content')

    <div class="row">
        <div class="col-md-6">
            <form method="post" action="{{ route('admin.users.search') }}">
                {!! csrf_field() !!}
                <input class="form-control" name="search"  value="{{ request('search') }}" placeholder="Search">
            </form>
        </div>
        <div class="col-md-6">
            <div class="btn-toolbar justify-content-end">
                <a class="btn btn-primary" href="{{ route('admin.users.invite') }}">Invite New User</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mt-3">
            @if ($users->isEmpty())
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
                        <table class="table table-borderless m-0 p-0">
                            <tbody>
                                @foreach($users as $user)
                                    @if ($user->id !== auth()->id())
                                        <tr>
                                            <td>{{ $user->email }}</td>
                                            <td class="text-right" width="140px">
                                                <div class="btn-toolbar justify-content-between">
                                                    <a class="btn btn-outline-primary btn-sm raw-margin-right-8" href="{{ url('admin/users/'.$user->id.'/edit') }}"><span class="fa fa-edit"></span> Edit</a>

                                                    {!! app(\App\Http\Forms\AdminUserForm::class)->confirm('Are you sure you want to delete this user?', 'confirmation')->delete($user) !!}
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
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
                                        <td class="text-right" width="225px">
                                            {!! form()->action('post',
                                                ['invite.resend', $invite],
                                                'Resend Invite',
                                                ['class' => 'btn btn-sm btn-outline-primary mr-2']
                                            ) !!}

                                            {!! form()->confirm('Are you sure you want to revoke this invite?', 'confirmation')
                                                ->action('post',
                                                ['invite.revoke', $invite],
                                                'Revoke Invite',
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
