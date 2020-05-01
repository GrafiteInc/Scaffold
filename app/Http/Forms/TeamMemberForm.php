<?php

namespace App\Http\Forms;

use Grafite\FormMaker\Fields\Email;
use Grafite\FormMaker\Fields\Select;
use Grafite\FormMaker\Forms\BaseForm;

class TeamMemberForm extends BaseForm
{
    public $buttons = [
        'submit' => 'Save',
    ];

    public $columns = 2;

    public $method = 'put';

    public $membership;

    public $email;

    public function fields()
    {
        return [
            Email::make('email', [
                'label' => 'Email',
                'value' => $this->email,
                'disabled' => 'disabled',
            ]),
            Select::make('team_role', [
                'required' => true,
                'label' => 'Select a Team Role',
                'options' => [
                    'Manager' => 'manager',
                    'Member' => 'member',
                ],
                'value' => $this->membership,
            ]),
        ];
    }

    public function setMember($member)
    {
        $this->membership = $member->membership->team_role;
        $this->email = $member->email;

        return $this;
    }
}
