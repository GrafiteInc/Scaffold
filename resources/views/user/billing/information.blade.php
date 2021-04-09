<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header">
                Billing Information
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input class="form-control" type="text" id="card-holder-name" required placeholder="Full Name" value="{{ $user->name }}" @if (isset($disabled)) disabled @endif>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="name">Email Address</label>
                            <input class="form-control" type="text" id="card-holder-email" required placeholder="Email Address" value="{{ $user->billing_email }}" @if (isset($disabled)) disabled @endif>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-0">
                            <label for="name">State</label>
                            <input class="form-control" type="text" id="card-holder-state" required placeholder="State" value="{{ $user->state }}" @if (isset($disabled)) disabled @endif>
                        </div>
                    </div>
                    <div class="col-md-6">
                        {!!
                            form()->makeField(\Grafite\Forms\Fields\Bootstrap\Country::class, 'country', [
                                'id' => 'card-holder-country',
                                'required' => true,
                                'disabled' => $user->country,
                                'value' => $user->country,
                            ]);
                        !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
