@extends('layouts.admin')

@section('page-title', 'Admin: Waitlist')

@section('admin_content')
    @php
        $badges = ['pending' => 'bg-secondary', 'invited' => 'bg-info', 'registered' => 'bg-success'];
    @endphp

    <div class="row">
        <div class="col-md-6 mb-3">
            <form method="get" action="{{ route('admin.waitlist.index') }}" role="search">
                <div class="input-group">
                    <input class="form-control" type="search" name="search" value="{{ request('search') }}" placeholder="Search Waitlist" aria-label="Search Waitlist">
                    <button class="btn btn-outline-primary" type="submit"><span class="fas fa-search"></span></button>
                </div>
            </form>
        </div>
        <div class="col-md-6 mb-3">
            <div class="btn-toolbar justify-content-end">
                <a class="btn btn-primary" href="{{ route('admin.waitlist.export') }}">
                    <span class="fas fa-fw fa-file-csv"></span>
                    Export CSV
                </a>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        @foreach ($counts as $status => $count)
            <div class="col-4">
                <div class="card shadow-sm text-center">
                    <div class="card-body py-2">
                        <div class="h4 m-0">{{ number_format($count) }}</div>
                        <span class="small text-body-secondary text-capitalize">{{ $status }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col-md-12">
            @if ($signups->isEmpty())
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <span>No waitlist signups found.</span>
                    </div>
                </div>
            @else
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h4 class="m-0">Waitlist</h4>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-borderless align-middle m-0 p-0">
                            <thead>
                                <tr>
                                    <th>Email</th>
                                    <th>Name</th>
                                    <th>Joined</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($signups as $signup)
                                    <tr>
                                        <td>{{ $signup->email }}</td>
                                        <td>{{ $signup->name }}</td>
                                        <td title="{{ $signup->created_at }}">{{ $signup->created_at->diffForHumans() }}</td>
                                        <td>
                                            <span class="badge {{ $badges[$signup->status] }} text-capitalize">{{ $signup->status }}</span>
                                        </td>
                                        <td width="220px" class="text-end">
                                            <div class="btn-toolbar justify-content-end">
                                                @if ($signup->status !== 'registered')
                                                    {!! form()->action('post', ['admin.waitlist.invite', $signup->id], $signup->invited_at ? 'Re-invite' : 'Invite', [
                                                        'class' => 'btn btn-outline-primary btn-sm',
                                                    ]) !!}
                                                @endif

                                                {!! $signup->form()->confirm('Are you sure you want to delete this signup?', 'app.confirmation')->delete($signup) !!}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{ $signups }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@stop
