@foreach ($data as $key => $value)
    <div class="d-flex gap-2 py-1 {{ $loop->last ? '' : 'border-bottom' }}">
        <div class="font-monospace text-body-secondary text-nowrap" style="min-width: 7rem;">{{ $key }}</div>
        <div class="flex-grow-1 font-monospace text-break overflow-hidden">
            @if (is_array($value) && ! empty($value))
                <div class="border-start ps-2">
                    @include('admin.users.partials.activity-value', ['data' => $value])
                </div>
            @elseif (is_bool($value))
                <span class="text-primary">{{ $value ? 'true' : 'false' }}</span>
            @elseif (is_null($value) || $value === '' || $value === [])
                <span class="text-body-secondary">—</span>
            @elseif ($value === \App\Models\Activity::MASK)
                <span class="text-body-secondary" title="Hidden">{{ $value }}</span>
            @else
                {{ $value }}
            @endif
        </div>
    </div>
@endforeach
