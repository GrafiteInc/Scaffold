<?php

namespace App\Http\Forms;

use App\Models\Role;
use App\Models\User;
use Grafite\FormMaker\Fields\Text;
use Grafite\FormMaker\Fields\Email;
use Grafite\FormMaker\Fields\Hidden;
use Grafite\FormMaker\Forms\ModelForm;
use Grafite\FormMaker\Fields\Bootstrap\HasMany;

class AdminUserForm extends ModelForm
{
    public $model = User::class;

    public $routePrefix = 'admin.users';

    public $with = [
        'roles'
    ];

    public $confirmMessage = 'Are you sure you want to delete this user?';

    public $confirmMethod = 'confirmation';

    public $buttons = [
        'submit' => 'Save',
        'edit' => '<span class="fas fa-fw fa-pencil-alt"></span> Edit',
        'delete' => '<span class="fas fa-fw fa-trash"></span> Delete'
    ];

    public $columns = 2;

    public $buttonClasses = [
        'submit' => 'btn btn-primary',
        'cancel' => 'btn btn-secondary',
        'edit' => 'btn btn-outline-primary btn-sm mr-2',
        'delete' => 'btn btn-outline-danger btn-sm',
    ];

    public function fields()
    {
        return [
            Text::make('name', [
                'required' => true,
                'sortable' => true,
            ]),
            Email::make('email', [
                'required' => true,
                'sortable' => true,
            ]),
            Hidden::make('role'),
            HasMany::make('roles', [
                'required' => true,
                'visible' => false,
                'model' => Role::class,
                'model_options' => [
                    'label' => 'label',
                ],
            ]),
        ];
    }
}
