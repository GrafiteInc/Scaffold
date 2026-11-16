<?php

namespace App\View\Forms;

use App\Models\WaitlistSignup;
use Grafite\Forms\Fields\Email;
use Grafite\Forms\Fields\Text;
use Grafite\Forms\Forms\ModelForm;

/**
 * Admin-side form for waitlist signups. Only its delete button is used;
 * signups are created from the public landing page.
 */
class WaitlistSignupForm extends ModelForm
{
    public $model = WaitlistSignup::class;

    public $routePrefix = 'admin.waitlist';

    public $buttons = [
        'delete' => '<span class="fas fa-fw fa-trash"></span> Delete',
    ];

    public $buttonClasses = [
        'delete' => 'btn btn-outline-danger btn-sm ms-2',
    ];

    public function fields()
    {
        return [
            Email::make('email'),
            Text::make('name'),
        ];
    }
}
