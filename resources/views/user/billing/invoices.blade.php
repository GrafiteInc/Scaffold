@extends('layouts.dashboard')

@section('page-title', 'Billing: Invoices')

@section('content')

    @include('user.billing.tabs')

    <div class="mt-4">
        <table class="table table-striped">
            <thead>
                <th>Date</th>
                <th>Identifier</th>
                <th class="text-right">Dollars</th>
            </thead>
            <tbody>
                @foreach($invoices as $invoice)
                    <tr>
                        <td>
                            <a href="{{ route('user.billing.invoice', [$invoice->id]) }}">
                                <span class="fas fa-fw fa-download"></span>
                                {{ $invoice->date()->format('Y-m-d') }}
                            </a>
                        </td>
                        <td>{{ $invoice->id }}</td>
                        <td class="text-right">${{ ($invoice->total / 100) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@stop
