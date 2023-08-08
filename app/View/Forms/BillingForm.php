<?php

namespace App\View\Forms;

use Grafite\Forms\Fields\Country;
use Grafite\Forms\Fields\Email;
use Grafite\Forms\Fields\Text;
use Grafite\Forms\Forms\BaseForm;

class BillingForm extends BaseForm
{
    public $route = 'user.billing.update';

    public $method = 'post';

    public $withJsValidation = true;

    public $buttons = [
        'submit' => 'Save',
    ];

    public $user;

    public $orientation = 'horizontal';

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
            Email::make('billing_email', [
                'label' => 'Email',
                'required' => $user->hasActiveSubscription(),
                'value' => $user->billing_email,
            ]),
            Text::make('state', [
                'label' => 'State',
                'required' => $user->hasActiveSubscription(),
                'value' => $user->state,
            ]),
            Country::make('country', [
                'label' => 'Country',
                'required' => $user->hasActiveSubscription(),
                'data-size' => 5,
                'value' => $user->country,
            ]),
        ];
    }
}
