<?php

namespace App\View\Forms;

use Grafite\Forms\Fields\Text;
use Grafite\Forms\Forms\BaseForm;

class TokenForm extends BaseForm
{
    public $route = 'user.create-token';

    public $buttons = [
        'submit' => 'Generate',
    ];

    public $buttonClasses = [
        'submit' => 'btn btn-block btn-primary',
    ];

    public function fields()
    {
        return [
            Text::make('name')
                ->required()
                ->withoutLabel()
                ->placeholder('Name'),
        ];
    }
}
