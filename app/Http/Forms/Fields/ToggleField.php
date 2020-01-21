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
     * Input attributes
     *
     * @return array
     */
    protected static function getAttributes()
    {
        return [
            'data-toggle' => 'toggle',
            'data-offstyle' => (auth()->user()->dark_mode) ? 'dark' : 'light',
            'data-size' => 'sm'
        ];
    }

    /**
     * Field maker options
     *
     * @return array
     */
    protected static function getOptions()
    {
        return [];
    }

    /**
     * View path for custom templates
     *
     * @return string
     */
    protected static function getView()
    {
        return null;
    }
}
