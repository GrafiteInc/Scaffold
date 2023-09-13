<?php

namespace App\View\Forms;

use Grafite\Forms\Fields\Select;
use Grafite\Forms\Forms\BaseForm;

class UserTwoFactorForm extends BaseForm
{
    public $route = 'user.two-factor.update';

    public $method = 'put';

    public $buttons = [
        'submit' => 'Save',
    ];

    public $columns = 1;

    public function fields()
    {
        return [
            Select::make('two_factor_platform', [
                'multiple' => false,
                'null_value' => true,
                'label' => 'Two Factor Platform',
                'options' => [
                    'Email' => 'email',
                    'Authenticator' => 'authenticator',
                ],
                'value' => auth()->user()->two_factor_platform,
            ]),
        ];
    }
}
