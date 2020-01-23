<div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header">
                Subscription Plan
            </div>
            <div class="card-body">
                <div class="form-group mb-0">
                    @if (!isset($unlabelled)) <label for="name">Plan</label> @endif
                    <select name="plan" id="card-holder-plan" class="form-control">
                        <option value="">Select a Plan</option>
                        @foreach(config('billing.plans') as $stripe_id => $plan)
                            <option value="{{ $stripe_id }}">{{ $plan['name'] }} (${{ $plan['price'] }} {{ $plan['frequency'] }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
