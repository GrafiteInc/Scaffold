@if (count($sessions) > 0)
    @foreach ($sessions as $session)
        <div class="row mb-4">
            <div class="col-md-1">
                @if ($session->agent->isDesktop())
                    <h3 class="m-0 mt-1"><span class="fa fa-desktop"></span></h3>
                @else
                    <h3 class="m-0 mt-1"><span class="fa fa-mobile"></span></h3>
                @endif
            </div>

            <div class="col-md-11">
                <div>
                    {{ $session->agent->platformName() ? $session->agent->platformName() : __('Unknown') }} - {{ $session->agent->browserName() ? $session->agent->browserName() : __('Unknown') }}
                </div>

                <div>
                    <div>
                        {{ $session->ip_address }},

                        @if ($session->is_current_device)
                            <span class="text-success">{{ __('This device') }}</span>
                        @else
                            {{ __('Last active') }} {{ $session->last_active }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif