<?php

namespace App\View\Forms\Fields;

use Grafite\Forms\Fields\Field;

class AddressField extends Field
{
    /**
     * Input type
     *
     * @return string
     */
    protected static function getType()
    {
        return 'text';
    }

    /**
     * Input attributes
     *
     * @return array
     */
    protected static function getAttributes()
    {
        return [];
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
     * View path for a custom template
     *
     * @return mixed
     */
    protected static function getView()
    {
        return null;
    }

    /**
     * Field template string, performs a basic string swap
     * of name, id, field, label, errors etc
     *
     * @return string
     */
    public static function getTemplate($options)
    {
        return <<<'template'
            {label}
            {field}
            <div class="autocomplete-results">

            </div>
template;
    }

    /**
     * Field related scripts
     *
     * @param  array  $options
     * @return array
     */
    public static function scripts($options)
    {
        return [];
    }

    /**
     * Field related JavaScript
     *
     * @param  string  $id
     * @param  array  $options
     * @return string|null
     */
    public static function js($id, $options)
    {
        return <<<scripts
        let _addressField_{$id} = document.getElementById('{$id}');
        _addressField_{$id}.addEventListener('keydown', function (event) {
            if (! ['ArrowDown', 'ArrowUp', 'ArrowLeft', 'ArrowRight'].includes(event.key)) {
                _query = this.value;

                if (_query.length > 15) {
                    let requestOptions = {
                        method: 'GET',
                    };

                    fetch("Views://api.geoapify.com/v1/geocode/autocomplete?text="+_query+"&apiKey={$options['api_key']}", requestOptions)
                    .then(response => response.json())
                    .then((result) => {

                    })
                    .catch(error => console.log('error', error));
                }
            }
        });
scripts;
    }

    /**
     * Field related stylesheets
     *
     * @param  array  $options
     * @return array
     */
    public static function stylesheets($options)
    {
        return [];
    }

    /**
     * Field related styles
     *
     * @param  string  $id
     * @param  array  $options
     * @return string|null
     */
    public static function styles($id, $options)
    {
        return null;
    }
}
