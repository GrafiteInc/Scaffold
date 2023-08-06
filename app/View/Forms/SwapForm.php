<?php

namespace App\View\Forms;

use Grafite\Forms\Fields\Select;
use Grafite\Forms\Forms\BaseForm;

class SwapForm extends BaseForm
{
    public $route = 'user.billing.swap';
    public $method = 'post';
    public $withJsValidation = true;

    public $buttons = [
        'submit' => 'Switch Plans',
    ];

    public $buttonClasses = [
        'submit' => 'btn btn-outline-primary',
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
                ->label('Change Plan to')
                ->required()
                ->singular()
                ->options(collect(config('billing.plans'))->pluck('key', 'label')->toArray())
                ->value(optional($user->subscription(config('billing.subscription_name')))->stripe_price),
        ];
    }
}
