@extends('layouts.admin')

@section('page-title') Admin: Users @stop

@section('admin_content')

    <div class="row">
        <div class="col-md-12">
            <div class="btn-toolbar justify-content-between">
                <form method="post" action="{{ route('admin.users.search') }}">
                    {!! csrf_field() !!}
                    <input class="form-control" name="search"  value="{{ request('search') }}" placeholder="Search">
                </form>

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
                <table class="table table-striped">
                    <thead>
                        <th>Email</th>
                        <th class="text-right" width="145px">Actions</th>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            @if ($user->id !== auth()->id())
                                <tr>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <div class="btn-toolbar justify-content-between">
                                            <a class="btn btn-outline-primary btn-sm raw-margin-right-8" href="{{ url('admin/users/'.$user->id.'/edit') }}"><span class="fa fa-edit"></span> Edit</a>

                                            {!! app(\App\Http\Forms\AdminUserForm::class)->confirm('Are you sure you want to delete this user?')->delete($user) !!}
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

@stop
