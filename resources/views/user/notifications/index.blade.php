@extends('layouts.user')

@section('page-title') Notifications @stop

@section('user_content')

    @if ($notifications->isEmpty())
        <div class="card mt-3 text-center shadow-sm">
            <div class="card-body">
                No notifications found.
            </div>
        </div>
    @else
        <table class="table table-striped">
            <thead>
                <th class="d-none d-md-table-cell">Date</th>
                <th class="d-none d-md-table-cell">Is Important</th>
                <th>Message</th>
                <th width="250px" class="text-right">Action</th>
            </thead>
            <tbody>
            @foreach ($notifications as $notification)
                <tr>
                    <td class="d-none d-md-table-cell">{{ $notification->created_at->format('d M, Y') }}</td>
                    <td class="d-none d-md-table-cell">@if ($notification->data['is_important']) <span class="fas fa-check"></span> @endif </td>
                    <td>{{ $notification->data['message'] }}</td>
                    <td class="text-right">
                        @if (is_null($notification->read_at))
                            {!! form()->action('post',
                                ['user.notifications.read', $notification->id],
                                'Mark as Read',
                                ['class' => 'btn btn-sm btn-outline-primary']
                            ) !!}
                        @endif

                        {!! form()
                            ->confirm('Are you sure you want to delete this?')
                            ->action('delete', ['user.notifications.destroy', $notification->id], 'Delete', ['class' => 'btn btn-sm btn-danger'])
                        !!}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif

@stop