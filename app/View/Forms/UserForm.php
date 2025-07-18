<?php

namespace App\View\Forms;

use App\Models\User;
use Grafite\Forms\Fields\Text;
use Grafite\Forms\Fields\Email;
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
        return [
            Text::make('name', [
                'required' => true,
            ]),
            // AddressField::make('address')->options(['api_key' => 'c00825250e754192870ccfdbaec2f801']),
            Email::make('email', [
                'required' => true,
            ]),
            Toggled::make('allow_email_based_notifications', [
                'legend' => 'Email Contact',
            ]),
            FileWithPreview::make('avatar', [
                'preview_identifier' => '.avatar',
                'preview_as_background_image' => true,
            ]),
        ];
    }
}
