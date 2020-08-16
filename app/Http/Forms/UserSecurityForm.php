<?php

namespace App\Http\Forms;

use Grafite\Forms\Fields\Password;
use Grafite\Forms\Forms\BaseForm;

class UserSecurityForm extends BaseForm
{
    public $route = 'user.security';

    public $method = 'put';

    public $orientation = 'horizontal';

    public $buttons = [
        'submit' => 'Save',
    ];

    public $columns = 1;

    public function fields()
    {
        return [
            Password::make('old_password', [
                'required' => true,
                'label' => 'Current Password',
            ]),
            Password::make('new_password', [
                'required' => true,
                'label' => 'New Password',
            ]),
            Password::make('new_password_confirmation', [
                'required' => true,
                'label' => 'Confirm New Password',
            ]),
        ];
    }
}
