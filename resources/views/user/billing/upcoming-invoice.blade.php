<div class="card shadow-sm">
    <div class="card-header">
        Upcoming Invoice
    </div>
    <div class="card-body">
        @if (!is_null($invoice))
            <table class="table">
                <tr>
                    <th>Date</th>
                    <th class="text-right">Cost</th>
                </tr>
                <tr>
                    <td>{{ $invoice->date()->format('F j, Y') }}</td>
                    <td class="text-right">&dollar;{{ $invoice->total/100 }}</td>
                </tr>
            </table>
        @else
            Next scheduled payment will appear here, please check again later.
        @endif
    </div>
</div>