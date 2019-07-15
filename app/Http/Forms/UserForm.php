<?php

namespace App\Http\Forms;

use App\Models\User;
use Grafite\FormMaker\Fields\Text;
use Grafite\FormMaker\Fields\Email;
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
        return [
            Text::make('name', [
                'required' => true,
            ]),
            Email::make('email', [
                'required' => true
            ]),
        ];
    }
}
