<?php

namespace App\View\Forms;

use Grafite\Forms\Fields\Text;
use Grafite\Forms\Forms\BaseForm;

class ConfirmTwoFactorForm extends BaseForm
{
    public $route = 'user.security.two-factor.confirm';

    public $buttons = [
        'submit' => 'Confirm',
    ];

    public $buttonClasses = [
        'submit' => 'btn btn-block btn-primary',
    ];

    public function fields()
    {
        return [
            Text::make('one_time_password')
                ->required()
                ->withoutLabel()
                ->placeholder('Authenticator Code'),
        ];
    }
}
