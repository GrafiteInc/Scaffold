@if (Gate::allows('team-manager', $team))
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
                                <td width="240px" class="text-right">
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
        @endif
    </div>
@endif
