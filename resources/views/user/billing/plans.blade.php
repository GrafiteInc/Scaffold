<div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header">
                Subscription Plan
            </div>
            <div class="card-body">
                    {!!
                        form()->makeField(\Grafite\Forms\Fields\Bootstrap\Select::class, 'plan', [
                            'id' => 'card-holder-plan',
                            'label' => (isset($unlabelled)) ? false : 'Plan',
                            'required' => true,
                            'multiple' => false,
                            'title' => 'Select a Plan',
                            'class' => 'form-control selectpicker',
                            'options' => collect(config('billing.plans'))->pluck('key', 'label')->toArray(),
                        ]);
                    !!}
            </div>
        </div>
    </div>
</div>
