<div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header">
                Subscription Plan
            </div>
            <div class="card-body">
                {!!
                    form()->makeField(\Grafite\Forms\Fields\Select::class, 'plan', [
                        'id' => 'card-holder-plan',
                        'label' => (isset($unlabelled)) ? false : 'Plan',
                        'required' => true,
                        'multiple' => false,
                        'title' => 'Select a Plan',
                        'value' => auth()->user()->subscription(config('billing.subscription_name'))->stripe_price,
                        'options' => collect(config('billing.plans'))->pluck('key', 'label')->toArray(),
                    ]);
                !!}
            </div>
        </div>
    </div>
</div>
