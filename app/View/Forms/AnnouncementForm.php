<?php

namespace App\View\Forms;

use Grafite\Forms\Forms\BaseForm;
use Grafite\Forms\Fields\Dropzone;
use Grafite\Forms\Fields\TextArea;

class AnnouncementForm extends BaseForm
{
    public $route = 'admin.announcements.store';

    public $method = 'post';

    public $buttons = [
        'submit' => 'Send',
    ];

    public function fields()
    {
        return [
            TextArea::make('message')
                ->required(),
        ];
    }
}
