<?php

namespace App\Http\Forms;

use App\Models\User;
use Grafite\FormMaker\Html\HrTag;
use Grafite\FormMaker\Fields\Text;
use Grafite\FormMaker\Fields\Email;
use Grafite\FormMaker\Forms\ModelForm;
use Grafite\FormMaker\Fields\FileWithPreview;
use Grafite\FormMaker\Fields\Bootstrap\Toggle;

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
                'required' => true,
            ]),
            Toggle::make('dark_mode', [
                'legend' => 'Dark Mode',
                'theme' => (auth()->user()->dark_mode) ? 'dark' : 'light',
            ]),
            Toggle::make('allow_email_based_notifications', [
                'legend' => 'Email Contact',
            ]),
            FileWithPreview::make('avatar', [
                'preview_identifier' => '.avatar',
                'preview_as_background_image' => true,
            ]),
        ], $this->billingColumns());
    }

    public function billingColumns()
    {
        return [
            HrTag::make([
                'class' => 'mt-4 mb-4'
            ]),
            Email::make('billing_email', [
                'label' => 'Billing Email',
                'required' => true,
            ]),
            Text::make('state', [
                'label' => 'Billing State',
                'required' => auth()->user()->hasActiveSubscription(),
            ]),
            Text::make('country', [
                'label' => 'Billing Country',
                'required' => auth()->user()->hasActiveSubscription(),
            ]),
        ];
    }
}
