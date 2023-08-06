<?php

namespace App\View\Forms;

use Grafite\Forms\Fields\Text;
use Grafite\Forms\Forms\BaseForm;

class CouponForm extends BaseForm
{
    public $route = 'user.billing.coupon';

    public $method = 'post';

    public $withJsValidation = true;

    public $buttons = [
        'submit' => 'Apply Coupon',
    ];

    public $buttonClasses = [
        'submit' => 'btn btn-outline-primary',
    ];

    public $disableOnSubmit = true;

    public function fields()
    {
        return [
            Text::make('coupon')
                ->required()
                ->placeholder('Coupon Code'),
        ];
    }
}
