<?php

namespace App\View\Forms;

use App\Services\WaitlistService;
use Grafite\Forms\Fields\Email;
use Grafite\Forms\Fields\Hidden;
use Grafite\Forms\Fields\Text;
use Grafite\Forms\Forms\BaseForm;
use Grafite\Forms\Html\HoneyPot;

class WaitlistForm extends BaseForm
{
    public $route = 'waitlist.store';

    public $columns = 'sections';

    public $buttons = [
        'submit' => 'Join the waitlist',
    ];

    public $buttonClasses = [
        'submit' => 'btn btn-lg btn-primary w-100 mt-2',
    ];

    public $disableOnSubmit = true;

    public function fields()
    {
        $tracked = collect(WaitlistService::TRACKED_PARAMS)
            ->filter(fn ($param) => request()->filled($param))
            ->map(fn ($param) => Hidden::make($param, ['value' => request()->query($param)]))
            ->values()
            ->all();

        return [
            HoneyPot::make()->name('honeypot'),
            Email::make('email', [
                'required' => true,
                'label' => 'Email',
                'placeholder' => 'you@example.com',
            ]),
            Text::make('name', [
                'label' => 'Name (optional)',
                'placeholder' => 'What should we call you?',
            ]),
            Hidden::make('landing_path', ['value' => '/'.ltrim(request()->path(), '/')]),
            ...$tracked,
        ];
    }

    public function setSections($fields)
    {
        $tracked = collect(WaitlistService::TRACKED_PARAMS)
            ->filter(fn ($param) => request()->filled($param))
            ->map(fn ($param) => [$param])
            ->values()
            ->all();

        return [
            ['honeypot'],
            ['email'],
            ['name'],
            ['landing_path'],
            ...$tracked,
        ];
    }
}
