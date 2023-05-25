<?php

namespace App\View\Components\Global;

use Grafite\Html\Tags\HtmlTagComponent;

class ConfirmationModal extends HtmlTagComponent
{
    public static function template()
    {
        return <<<HTML
            <div class="modal fade" role="dialog" id="confirmationModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                Confirmation
                            </h5>
                            <button type="button" class="btn-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p class="mb-4" id="confirmationModalMessage"></p>
                            <button onclick="window.submitConfirmationForm(event)" class="float-end btn btn-outline-primary">
                                Confirm
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        HTML;
    }

    public static function js()
    {
        return <<<JS
            window.submitConfirmationForm = function (event) {
                let _processing = '<i class="fas fa-circle-notch fa-spin mr-2"></i>';
                event.target.innerHTML = _processing + event.target.innerHTML;
                event.target.disabled = true;

                window.confirmationFormTarget.closest('form').submit();
            }

            window.confirmation = (_event, _message) => {
                _event.preventDefault();

                document.getElementById('confirmationModalMessage').innerHTML = _message;
                let _modal = window.bootstrap.Modal.getOrCreateInstance(document.getElementById('confirmationModal'));
                _modal.toggle();
                window.confirmationFormTarget = _event.target;

                return false;
            };
        JS;
    }
}
