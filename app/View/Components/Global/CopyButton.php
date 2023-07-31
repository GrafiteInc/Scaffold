<?php

namespace App\View\Components\Global;

use Grafite\Html\Tags\HtmlTagComponent;

class CopyButton extends HtmlTagComponent
{
    public static function template()
    {
        $data = self::$data['payload'];
        $css = self::$data['css'] ?? 'btn btn-outline-secondary';
        $text = self::$data['text'] ?? 'Copy';

        return <<<HTML
            <button
                class="{$css} global-copy-button"
                data-payload="{$data}"
            >
                <span class="fa fa-copy"></span> {$text}
            </button>
        HTML;
    }

    public static function js()
    {
        return <<<'JS'
            document.querySelectorAll('.global-copy-button').forEach(function (_button) {
                _button.addEventListener('click', function (event) {
                    navigator.clipboard.writeText(_button.getAttribute('data-payload')); window.app.notify.success('Copied!');
                });
            });
        JS;
    }
}
