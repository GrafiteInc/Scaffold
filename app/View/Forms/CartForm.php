<?php

namespace App\View\Forms;

use Grafite\Forms\Fields\Number;
use Grafite\Forms\Forms\LivewireForm;
use Grafite\Forms\Html\Button;

class CartForm extends LivewireForm
{
    public $columns = 2;

    /**
     * Set the desired fields for the form.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Number::make('count', [
                'required' => true,
                'label' => false,
                'wrapper' => false,
            ]),
            Button::make([
                'wire:click.prevent' => 'setNumber',
                'content' => '<span class="fas fa-fw fa-plus pr-4"></span> Add to Cart',
            ], 'add'),
        ];
    }
}
