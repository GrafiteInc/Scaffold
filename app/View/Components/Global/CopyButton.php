<?php

namespace App\View\Components\Global;

use Grafite\Html\Tags\HtmlTagComponent;

class CopyButton extends HtmlTagComponent
{
    public static function template()
    {
        $data = self::$data[0];

        return <<<HTML
            <button
                onclick="navigator.clipboard.writeText('{$data}'); window.notify.success('copied!');"
                class="btn btn-outline-secondary"
            >
                <span class="fa fa-copy"></span> Copy
            </button>
        HTML;
    }
}
