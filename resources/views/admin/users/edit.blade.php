@extends('layouts.admin')

@section('page-title', 'Admin: Edit User')

@section('admin_content')

    @if ($user->isDisabled())
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-danger d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
                    <div>
                        <span class="fas fa-fw fa-ban"></span>
                        <strong>Disabled</strong>
                        {{ $user->disabled_until ? 'until '.$user->disabled_until->format('M d, Y g:i A') : 'permanently' }}
                        @if ($user->disabledBy)
                            by {{ $user->disabledBy->name }}
                        @elseif (! $user->disabled_by)
                            (automatically)
                        @endif
                        @if ($user->disabled_reason)
                            <div class="small mt-1">{{ $user->disabled_reason }}</div>
                        @endif
                    </div>
                    @can('has-permissions', 'users.disable')
                        {!! form()->action('post', ['admin.users.enable', $user->id], 'Enable User', ['class' => 'btn btn-outline-success btn-sm']) !!}
                    @endcan
                </div>
            </div>
        </div>
    @endif

    @if ($user->needsPasswordReset())
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-warning d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
                    <div>
                        <span class="fas fa-fw fa-key"></span>
                        <strong>Password change required</strong> on their next visit.
                    </div>
                    @can('has-permissions', 'users.password-reset')
                        {!! form()->action('post', ['admin.users.clear-password-reset', $user->id], 'Cancel Requirement', ['class' => 'btn btn-outline-secondary btn-sm']) !!}
                    @endcan
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12 d-flex justify-content-end">
            @if (! session('original_user') && ! $user->isDisabled())
                {!! form()->action('post', ['admin.users.switch', $user->id], 'Login as this User', [ 'class' => 'btn btn-outline-secondary' ]) !!}
            @endif
            @if (! $user->needsPasswordReset() && ! $user->is(auth()->user()))
                @can('has-permissions', 'users.password-reset')
                    {!! form()->action('post', ['admin.users.require-password-reset', $user->id], 'Require Password Reset', ['class' => 'btn btn-outline-warning ms-2']) !!}
                @endcan
            @endif
            @if (! $user->isDisabled() && ! $user->is(auth()->user()))
                @can('has-permissions', 'users.disable')
                    {!! app(\App\View\Forms\DisableUserForm::class)->setRoute('admin.users.disable', [$user->id])->make() !!}
                @endcan
            @endif
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <x-f-model
                    :form="\App\View\Forms\AdminUserForm::class"
                    :model="$user"
                    action="edit"
                />
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            @if ($activities->isEmpty())
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        No known activities
                    </div>
                </div>
            @else
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h4 class="m-0">Activities <small>(Last 25)</small></h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless table-hover p-0 m-0">
                            @foreach($activities as $activity)
                                <tr onclick="window.app.modal('Activity', '{{ $activity->forModal() }}')">
                                    <td>{{ $activity->description }}</td>
                                    <td width="250px" class="d-none d-sm-table-cell">
                                        <b>{{ $activity->request['method'] }}:</b> {{ Str::limit(str_replace(url('/'), '', $activity->request['url']), 20) }}
                                    </td>
                                    <td width="180px" class="text-end">{{ $activity->created_at->format('M d, Y') }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>

@stop