@extends('layouts.user')

@section('page-title') Notifications @stop

@section('user_content')

    @if ($notifications->isEmpty())
        <div class="card mt-3 text-center shadow-sm">
            <div class="card-body">
                No notifications, that's like inbox ZERO.
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="m-0">App Notifications</h4>
                    </div>
                    <div class="col-md-6 text-right">
                        {!! form()->confirm('Are you sure you want to delete all notifications?', 'confirmation')
                            ->action('delete', 'user.notifications.clear',
                                'Delete All',
                                ['class' => 'btn btn-sm btn-outline-danger']
                        ) !!}
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-borderless m-0 p-0">
                    <tbody>
                    @foreach ($notifications as $notification)
                        <tr @if ($notification->data['is_important']) class="text-warning" @endif>
                            <td width="140px" class="d-none d-md-table-cell">{{ $notification->created_at->format('d M, Y') }}</td>
                            <td>{{ $notification->data['message'] }}</td>
                            <td width="250px" class="text-right">
                                @if (is_null($notification->read_at))
                                    {!! form()->action('post',
                                        ['user.notifications.read', $notification->id],
                                        'Mark as Read',
                                        ['class' => 'btn btn-sm btn-outline-primary mr-2']
                                    ) !!}
                                @endif

                                {!! form()
                                    ->confirm('Are you sure you want to delete this?', 'confirmation')
                                    ->action('delete', ['user.notifications.destroy', $notification->id], 'Delete', ['class' => 'btn btn-sm btn-danger'])
                                !!}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

@stop
