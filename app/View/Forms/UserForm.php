<?php

namespace App\View\Forms;

use App\Models\User;
use Grafite\Forms\Html\HrTag;
use Grafite\Forms\Fields\Text;
use Grafite\Forms\Fields\Email;
use Grafite\Forms\Html\Heading;
use Grafite\Forms\Fields\Select;
use Grafite\Forms\Fields\Country;
use Grafite\Forms\Fields\Toggled;
use Grafite\Forms\Forms\ModelForm;
use Grafite\Forms\Fields\FileWithPreview;

class UserForm extends ModelForm
{
    public $model = User::class;

    public $routePrefix = 'user';

    public $withJsValidation = true;

    public $buttons = [
        'submit' => 'Save',
        'delete' => '<span class="fas fa-fw fa-trash"></span> Delete',
    ];

    public $columns = 1;

    public $orientation = 'horizontal';

    public $formId = 'UserForm';

    public $hasFiles = true;

    public $disableOnSubmit = true;

    public function fields()
    {
        return array_merge([
            Text::make('name', [
                'required' => true,
            ]),
            // AddressField::make('address')->options(['api_key' => 'c00825250e754192870ccfdbaec2f801']),
            Email::make('email', [
                'required' => true,
            ]),
            Toggled::make('allow_email_based_notifications', [
                'legend' => 'Email Contact',
                'color' => '#8558da',
            ]),
            FileWithPreview::make('avatar', [
                'preview_identifier' => '.avatar',
                'preview_as_background_image' => true,
            ]),
            Select::make('two_factor_platform', [
                'multiple' => false,
                'null_value' => true,
                'label' => 'Two Factor Platform',
                'options' => [
                    'Email' => 'email',
                    'Authenticator' => 'authenticator',
                ],
                'value' => auth()->user()->two_factor_platform,
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
                'required' => auth()->user()->hasActiveSubscription(),
            ]),
            Text::make('state', [
                'label' => 'State',
                'required' => auth()->user()->hasActiveSubscription(),
            ]),
            Country::make('country', [
                'label' => 'Country',
                'required' => auth()->user()->hasActiveSubscription(),
                'data-size' => 5,
            ]),
        ];
    }
}
