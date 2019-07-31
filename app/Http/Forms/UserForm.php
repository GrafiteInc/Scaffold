<?php

namespace App\Http\Forms;

use App\Models\Role;
use App\Models\User;
use Grafite\FormMaker\Fields\Text;
use Grafite\FormMaker\Fields\Email;
use Grafite\FormMaker\Fields\HasMany;
use Grafite\FormMaker\Forms\ModelForm;

class UserForm extends ModelForm
{
    public $model = User::class;

    public $routePrefix = 'user';

    public $buttons = [
        'save' => 'Save',
    ];

    public $columns = 2;

    public function fields()
    {
        return array_merge([
            Text::make('name', [
                'required' => true,
            ]),
            Email::make('email', [
                'required' => true
            ]),
        ], $this->adminFeilds());
    }

    public function adminFeilds()
    {
        if (auth()->user()->hasRole('admin')) {
            return [
                HasMany::make('roles', [
                    'required' => true,
                    'model' => Role::class,
                    'model_options' => [
                        'label' => 'label'
                    ]
                ])
            ];
        }

        return [];
    }
}
