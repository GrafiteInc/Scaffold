<?php

namespace App\View\Forms;

use Grafite\Forms\Fields\Bootstrap\Select;
use Grafite\Forms\Forms\BaseForm;

class SubscribeForm extends BaseForm
{
    public $route = 'user.billing.subscribe';

    public $method = 'post';

    public $withJsValidation = true;

    public $buttons = [
        'submit' => 'Subscribe',
    ];

    public $user;

    public $disableOnSubmit = true;

    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    public function fields()
    {
        $user = $this->user;

        return [
            Select::make('plan')
                ->id('card-holder-plan')
                ->label('Plan')
                ->required()
                ->singular()
                ->options(collect(config('billing.plans'))->pluck('key', 'label')->toArray())
                ->value(optional($user->subscription(config('billing.subscription_name')))->stripe_price),
        ];
    }
}
