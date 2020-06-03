<?php

namespace App\Http\Forms;

use App\Models\User;
use Grafite\FormMaker\Fields\Bootstrap\Toggle;
use Grafite\FormMaker\Fields\Email;
use Grafite\FormMaker\Fields\FileWithPreview;
use Grafite\FormMaker\Fields\Text;
use Grafite\FormMaker\Forms\ModelForm;
use Grafite\FormMaker\Html\Heading;
use Grafite\FormMaker\Html\HrTag;

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
            Heading::make([
                'class' => 'mt-4 mb-1',
                'content' => 'Billing Details',
                'level' => 4,
            ]),
            HrTag::make(),
            Email::make('billing_email', [
                'label' => 'Email',
                'required' => true,
            ]),
            Text::make('state', [
                'label' => 'State',
                'required' => auth()->user()->hasActiveSubscription(),
            ]),
            Text::make('country', [
                'label' => 'Country',
                'required' => auth()->user()->hasActiveSubscription(),
            ]),
        ];
    }
}
