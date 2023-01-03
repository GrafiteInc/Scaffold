<?php

namespace App\View\Forms;

use Grafite\Forms\Fields\Password;
use Grafite\Forms\Forms\ComponentForm;

class LogoutForm extends ComponentForm
{
    /**
     * The form route.
     *
     * If you need to inject a parameter
     * to the route, then use the `setRoute`
     * method.
     *
     * @var string
     */
    public $route = 'user.logout';

    /**
     * Buttons and values.
     *
     * @var array
     */
    public $buttons = [
        'submit' => 'Logout other devices',
    ];

    /**
     * Button classes.
     *
     * @var array
     */
    public $buttonClasses = [
        'submit' => 'btn btn-warning',
    ];

    /**
     * Trigger content (usually a button)
     *
     * @var string
     */
    public $triggerContent = '<span class="fas fa-fw fa-sign-out-alt"></span> Logout other devices';

    /**
     * Trigger class (usually for a button)
     *
     * @var string
     */
    public $triggerClass = 'btn d-block w-100 btn-outline-warning mb-3';

    /**
     * Set the desired fields for the form.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Password::make('password', [
                'required' => true,
            ]),
        ];
    }
}
