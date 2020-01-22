<?php

/*
|--------------------------------------------------------------------------
| Form Maker Config
|--------------------------------------------------------------------------
*/

return [

    'form' => [
        'group-class' => 'form-group',
        'label-class' => 'control-label',
        'before_after_input_wrapper' => 'input-group',
        'text-error' => 'text-danger',
        'error-class' => 'has-error',

        /*
        * --------------------------------------------------------------------------
        * Form Sections
        * --------------------------------------------------------------------------
        */

        'sections' => [
            'column-base' => 'col-md-',
            'row-class' => 'row',
            'full-size-column' => 'col-md-12',
            'header-spacing' => 'mt-2 mb-2',
        ],

        /*
         * --------------------------------------------------------------------------
         * For Horizontal forms
         * --------------------------------------------------------------------------
         *  You may wish to use horizontal forms. If you do, you need to set the
         *  orientation to horizontal, and ensure that your form has the 'form-horizontal'
         *  class.
        */

        'orientation' => 'vertical',
        'label-column' => 'col-md-2 col-form-label',
        'input-column' => 'col-md-10',
    ]

];
