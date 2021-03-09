<?php

namespace App\Http\Forms;

use Grafite\Forms\Fields\Email;
use Grafite\Forms\Forms\BaseForm;
use Grafite\Forms\Fields\Bootstrap\Select;

class TeamMemberForm extends BaseForm
{
    public $buttons = [
        'submit' => 'Save',
        'delete' => '<span class="fas fa-fw fa-trash"></span> Delete',
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
                'multiple' => false,
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
