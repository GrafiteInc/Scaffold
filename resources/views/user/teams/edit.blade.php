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
                @if ($team->members->isEmpty())
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    No team members.
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h4 class="m-0">Members</h4>
                        </div>
                        <div class="card-body">
                            <table class="table p-0 m-0 table-borderless">
                                <tbody>
                                    @foreach($team->members as $member)
                                        <tr>
                                            <td>{{ $member->name }}</td>
                                            <td width="220px">
                                                @if (Gate::forUser($member)->allows('admin-team', $team))
                                                    {!! form()->confirm('Are you sure you want to remove this member?', 'confirmation')
                                                        ->action('delete',
                                                        ['teams.remove', [$team, $member]],
                                                        'Remove',
                                                        ['class' => 'btn btn-sm btn-outline-danger pull-right']
                                                    ) !!}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        @if ($team->admin === auth()->id())
            <div class="col-md-12 mt-4">
                @if ($team->invites->isEmpty())
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    No team member invites.
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h4 class="m-0">Invites</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless p-0 m-0">
                            <tbody>
                                @foreach($team->invites as $invite)
                                    <tr>
                                        <td>{{ $invite->email }}</td>
                                        <td>
                                            @if ($invite->user)
                                                <span class="badge badge-primary badge-pill">Registered User</span>
                                            @endif
                                        </td>
                                        <td width="220px" class="text-right">
                                            {!! form()->action('post',
                                                ['invite.resend', $invite],
                                                'Resend Invite',
                                                ['class' => 'btn btn-sm btn-outline-primary']
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
                @endif
            </div>
        @endif
    </div>

@stop
