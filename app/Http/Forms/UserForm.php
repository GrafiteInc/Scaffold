<?php

namespace App\Http\Forms;

use App\Models\Role;
use App\Models\User;
use Grafite\FormMaker\Fields\File;
use Grafite\FormMaker\Fields\Text;
use Grafite\FormMaker\Fields\Email;
use App\Http\Forms\Fields\ToggleField;
use Grafite\FormMaker\Fields\Checkbox;
use Grafite\FormMaker\Forms\ModelForm;

class UserForm extends ModelForm
{
    public $model = User::class;

    public $routePrefix = 'user';

    public $buttons = [
        'submit' => 'Save',
    ];

    public $columns = 1;

    public $orientation = 'horizontal';

    public $hasFiles = true;

    public function fields()
    {
        return array_merge([
            Text::make('name', [
                'required' => true,
            ]),
            Email::make('email', [
                'required' => true
            ]),
            ToggleField::make('dark_mode', [
                'legend' => 'Dark Mode'
            ]),
            ToggleField::make('allow_email_based_notifications', [
                'legend' => 'Email Contact'
            ]),
            File::make('avatar', [

            ]),
        ], $this->billingColumns());
    }

    public function billingColumns()
    {
        return [
            Email::make('billing_email', [
                'label' => 'Billing Email',
                'required' => true
            ]),
            Text::make('state', [
                'required' => auth()->user()->hasActiveSubscription(),
            ]),
            Text::make('country', [
                'required' => auth()->user()->hasActiveSubscription(),
            ]),
        ];
    }
}
