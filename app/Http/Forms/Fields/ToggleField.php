<?php

namespace App\Http\Forms\Fields;

use Grafite\FormMaker\Fields\Field;

class ToggleField extends Field
{
    /**
     * Input type
     *
     * @return string
     */
    protected static function getType()
    {
        return 'checkbox';
    }

    /**
     * Input options
     *
     * @return array
     */
    protected static function getOptions()
    {
        return [
            'label' => false,
        ];
    }

    /**
     * Input attributes
     *
     * @return array
     */
    protected static function getAttributes()
    {
        return [
            'data-toggle' => 'toggle',
            'data-offstyle' => (auth()->user()->dark_mode) ? 'dark' : 'light',
            'data-size' => 'sm',
        ];
    }
}
