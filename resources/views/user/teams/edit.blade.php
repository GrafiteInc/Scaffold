@extends('layouts.user')

@section('page-title') Teams: Edit @stop

@section('user_content')

    <div class="row">
        <div class="col-md-6 mb-4">
            <div>
                {!! $form !!}
            </div>
        </div>
        @if ($team->admin === auth()->id())
            <div class="col-md-6 mb-4">
                {!! $inviteForm !!}
            </div>
        @endif

        @if ($team->admin === auth()->id())
            <div class="col-md-12">
                <h3 class="text-left">Members</h3>
                @if ($team->members->isEmpty())
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    No members found.
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <table class="table table-striped">
                        <thead>
                            <th>Name</th>
                            <th class="text-right">Action</th>
                        </thead>
                        <tbody>
                            @foreach($team->members as $member)
                                <tr>
                                    <td>{{ $member->name }}</td>
                                    <td>
                                        @if (Gate::forUser($member)->allows('admin-team', $team))
                                            <a class="btn btn-danger pull-right btn-sm" href="{{ url('teams/'.$team->id.'/remove/'.$member->id) }}" onclick="return confirm('Are you sure you want to remove this member?')">Remove</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        @endif

        @if ($team->admin === auth()->id())
            <div class="col-md-12 mt-4">
                <h3 class="text-left">Invites</h3>
                @if ($team->invites->isEmpty())
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    No invites found.
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <table class="table table-striped">
                        <thead>
                            <th>Email</th>
                            <th width="228px" class="text-right">Actions</th>
                        </thead>
                        <tbody>
                            @foreach($team->invites as $invite)
                                <tr>
                                    <td>{{ $invite->email }}</td>
                                    <td class="text-right">
                                        {!! form()->action('post',
                                            ['invite.resend', $invite],
                                            'Resend Invite',
                                            ['class' => 'btn btn-sm btn-outline-primary']
                                        ) !!}

                                        {!! form()->confirm('Are you sure you want to revoke this invite?')
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
                @endif
            </div>
        @endif
    </div>

@stop
