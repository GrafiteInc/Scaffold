<?php

namespace App\Http\Forms;

use App\Models\Team;
use App\Models\User;
use Grafite\FormMaker\Fields\Text;
use Grafite\FormMaker\Fields\Email;
use Grafite\FormMaker\Forms\ModelForm;

class TeamForm extends ModelForm
{
    public $model = Team::class;

    public $routePrefix = 'user.teams';

    public $buttons = [
        'save' => 'Save',
    ];

    public $columns = 1;

    /**
     * Form button classes
     *
     * @var array
     */
    public $buttonClasses = [
        'save' => 'btn btn-primary',
        'cancel' => 'btn btn-secondary',
        'delete' => 'btn btn-sm btn-danger',
    ];

    public function fields()
    {
        return [
            Text::make('name', [
                'required' => true,
            ])
        ];
    }
}
