<div class="row">
    <div class="col-md-6 mt-4">
        <div class="card shadow-sm">
            <div class="card-header">
                Current Subscription Plan
            </div>
            <div class="card-body">
                <div class="form-group mb-3">
                    <label for="name" class="form-label">Plan</label>
                    <input type="text" value="{{ auth()->user()->subscriptionPlan('name') }}" disabled class="form-control">
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 mt-4">
        <div class="card shadow-sm">
            <div class="card-header">
                Current Payment Method
            </div>
            <div class="card-body">
                <div class="form-group mb-3">
                    <label for="number" class="form-label">Credit Card</label>
                    <input class="form-control" disabled type="text" name="number" value="**** **** **** {{ $user->card_last_four }}">
                </div>
            </div>
        </div>
    </div>
</div>
