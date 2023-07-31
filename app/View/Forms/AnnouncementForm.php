<?php

namespace App\View\Forms;

use Grafite\Forms\Fields\TextArea;
use Grafite\Forms\Forms\BaseForm;

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
