<?php

namespace App\Http\Forms;

use App\Models\Role;
use App\Models\User;
use Grafite\FormMaker\Fields\Text;
use Grafite\FormMaker\Fields\Email;
use Grafite\FormMaker\Fields\HasMany;
use Grafite\FormMaker\Forms\ModelForm;

class AdminUserForm extends ModelForm
{
    public $model = User::class;

    public $routePrefix = 'admin.users';

    public $buttons = [
        'submit' => 'Save',
    ];

    public $columns = 2;

    public $buttonClasses = [
        'submit' => 'btn btn-primary',
        'cancel' => 'btn btn-secondary',
        'delete' => 'btn btn-danger btn-sm',
    ];

    public function fields()
    {
        return [
            Text::make('name', [
                'required' => true,
            ]),
            Email::make('email', [
                'required' => true,
            ]),
            HasMany::make('roles', [
                'required' => true,
                'model' => Role::class,
                'model_options' => [
                    'label' => 'label',
                ],
            ]),
        ];
    }
}
