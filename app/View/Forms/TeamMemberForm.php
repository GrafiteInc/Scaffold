<?php

namespace App\View\Forms;

use Grafite\Forms\Fields\Email;
use Grafite\Forms\Forms\ComponentForm;
use Grafite\Forms\Fields\Bootstrap\Select;

class TeamMemberForm extends ComponentForm
{
    public $buttons = [
        'submit' => 'Save',
        'delete' => '<span class="fas fa-fw fa-trash"></span> Delete',
    ];

    public $columns = 2;

    public $method = 'put';

    public $member;

    public $disableOnSubmit = true;

    public function __construct($member, $team)
    {
        $this->member = $member;
        $this->team = $team;

        $this->setRoute('teams.members.update', [$this->team->id, $this->member->id]);

        parent::__construct();
    }

    public function fields()
    {
        return [
            Email::make('email', [
                'label' => 'Email',
                'value' => $this->member->email,
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
                'value' => $this->member->membership->team_role,
            ]),
        ];
    }
}
