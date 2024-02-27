<?php

namespace App\View\Forms;

use Grafite\Forms\Fields\Email;
use Grafite\Forms\Fields\PasswordWithReveal;
use Grafite\Forms\Fields\Text;
use Grafite\Forms\Forms\BaseForm;
use Grafite\Forms\Html\HoneyPot;

class RegistrationForm extends BaseForm
{
    public $route = 'register';

    public $columns = 'sections';

    public $buttons = [
        'submit' => 'Register',
        'cancel' => 'Already Registered?',
    ];

    public $buttonLinks = [
        'cancel' => 'login',
    ];

    public $buttonClasses = [
        'submit' => 'btn mt-3 btn-primary',
        'cancel' => 'btn mt-3 btn-text',
    ];

    public function fields()
    {
        return [
            HoneyPot::make(),
            Text::make('name', [
                'required' => true,
                'label' => 'Name',
                'placeholder' => 'Full Name',
            ]),
            Email::make('email', [
                'required' => true,
                'label' => 'Email',
                'placeholder' => 'Email',
            ]),
            PasswordWithReveal::make('password', [
                'required' => true,
                'label' => 'Password',
                'placeholder' => 'At least 8 characters',
                'toggle' => '<span class="fas fa-eye"></span>',
            ]),
            PasswordWithReveal::make('password_confirmation', [
                'required' => true,
                'label' => 'Confirm Password',
                'placeholder' => 'At least 8 characters',
                'toggle' => '<span class="fas fa-eye"></span>',
            ]),
        ];
    }

    public function setSections($fields)
    {
        return [
            ['honeypot'],
            ['name'],
            ['email'],
            ['password'],
            ['password_confirmation'],
            ['registered'],
        ];
    }
}
