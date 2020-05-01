<?php

namespace App\Http\Forms;

use Grafite\FormMaker\Fields\Email;
use Grafite\FormMaker\Forms\BaseForm;

class TeamInviteForm extends BaseForm
{
    public $buttons = [
        'submit' => 'Invite',
    ];

    public $columns = 1;

    public function fields()
    {
        return [
            Email::make('email', [
                'required' => true,
                'label' => 'Invite a new member',
                'placeholder' => 'Email Address',
            ]),
        ];
    }
}
