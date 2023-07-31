<?php

namespace App\View\Forms;

use App\Models\Role;
use App\Models\User;
use Grafite\Forms\Fields\Text;
use Grafite\Forms\Fields\Email;
use Grafite\Forms\Fields\Hidden;
use Grafite\Forms\Forms\ModelForm;
use Grafite\Forms\Fields\Bootstrap\HasOne;

class AdminUserForm extends ModelForm
{
    public $model = User::class;

    public $routePrefix = 'admin.users';

    public $isCardForm = true;

    public $disableOnSubmit = true;

    public $deleteAsModal = true;

    public $with = [
        'roles',
    ];

    public $confirmMessage = 'Are you sure you want to delete this user?';

    public $buttons = [
        'submit' => 'Save',
        'edit' => '<span class="fas fa-fw fa-pencil-alt"></span> Edit',
        'delete' => '<span class="fas fa-fw fa-trash"></span> Delete',
    ];

    public $columns = 'sections';

    public $buttonClasses = [
        'submit' => 'btn btn-primary',
        'cancel' => 'btn btn-secondary',
        'edit' => 'btn btn-outline-primary btn-sm me-2',
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
                'table_class' => 'd-none d-sm-table-cell',
            ]),
            Hidden::make('role', [
                'table_class' => 'd-none d-sm-table-cell',
            ]),
            HasOne::make('role', [
                'required' => true,
                'visible' => false,
                'table_class' => 'd-none d-sm-table-cell',
                'model' => Role::class,
                'model_options' => [
                    'label' => 'label',
                ],
            ]),
        ];
    }

    public function setSections($fields)
    {
        return [
            ['name', 'email'],
            ['role'],
        ];
    }
}
