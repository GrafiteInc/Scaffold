<?php

namespace App\View\Components\Global;

use Grafite\Html\Tags\HtmlTagComponent;

class ContentModal extends HtmlTagComponent
{
    public static function template()
    {
        return <<<HTML
            <div class="modal fade" role="dialog" id="contentModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="contentModalTitle"></h5>
                            <button type="button" class="btn-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body bmx-overflow-x-hidden">
                            <span id="contentModalMessage"></span>
                        </div>
                    </div>
                </div>
            </div>
        HTML;
    }

    public static function js()
    {
        return <<<JS
            window.modal = (_title, _message) => {
                document.getElementById('contentModalTitle').innerHTML = _title;
                document.getElementById('contentModalMessage').innerHTML = _message;

                let _modal = window.bootstrap.Modal.getOrCreateInstance(document.getElementById('contentModal'));
                _modal.toggle();
            };
        JS;
    }
}
