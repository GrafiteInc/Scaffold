<?php

namespace App\View\Components\Global;

use Grafite\Html\Tags\HtmlTagComponent;

class Offcanvas extends HtmlTagComponent
{
    public static function template()
    {
        return <<<HTML
            <div id="offCanvas" class="offcanvas offcanvas-end" tabindex="-1" aria-labelledby="offCanvasTitle">
                <div class="offcanvas-header">
                    <h5 id="offCanvasTitle"></h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <span id="offCanvasMessage"></span>
                </div>
            </div>
        HTML;
    }

    public static function js()
    {
        return <<<JS
            window.offCanvas = (_title, _message) => {
                document.getElementById('offCanvasTitle').innerHTML = _title;
                document.getElementById('offCanvasMessage').innerHTML = _message;

                let _offcanvas = window.bootstrap.Offcanvas.getOrCreateInstance(document.getElementById('offCanvas'));
                _offcanvas.toggle();
            };
        JS;
    }
}
