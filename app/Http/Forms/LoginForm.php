<?php

namespace App\Http\Forms;

use Grafite\Forms\Html\Link;
use Grafite\Forms\Fields\Email;
use Grafite\Forms\Html\HoneyPot;
use Grafite\Forms\Forms\BaseForm;
use Grafite\Forms\Fields\CheckboxInline;
use Grafite\Forms\Fields\PasswordWithReveal;

class LoginForm extends BaseForm
{
    public $route = 'login';

    public $columns = 'sections';

    public $buttons = [
        'submit' => 'Login',
    ];

    public $buttonClasses = [
        'submit' => 'btn btn-block mt-3 btn-primary',
    ];

    public function fields()
    {
        return [
            HoneyPot::make(),
            Email::make('email', [
                'required' => true,
                'label' => 'Email',
                'placeholder' => 'Email',
            ]),
            PasswordWithReveal::make('password', [
                'required' => true,
                'label' => 'Password',
                'placeholder' => 'Password',
                'toggle' => '<span class="fas fa-eye"></span>',
            ]),
            CheckboxInline::make('remember', [
                'class' => 'mt-3',
                'label-class' => 'mt-3',
                'label' => 'Remember Me',
            ]),
            Link::make([
                'content' => 'Forgot Password?',
                'class' => 'd-block mt-3 text-right',
                'href' => route('password.request'),
            ], 'forgot_password'),
        ];
    }

    public function setSections()
    {
        return [
            ['honeypot'],
            ['email'],
            ['password'],
            ['remember','forgot_password'],
        ];
    }
}
