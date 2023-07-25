<?php

namespace App\View\Components\Global;

use Grafite\Html\Tags\HtmlTagComponent;

class ConfirmationModal extends HtmlTagComponent
{
    public static function template()
    {
        return <<<HTML
            <div class="modal fade" role="dialog" id="confirmationModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                Confirmation
                            </h5>
                            <button type="button" class="btn-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p class="mb-4" id="confirmationModalMessage"></p>
                            <button id="confirmationModalConfirmButton" class="float-end btn btn-outline-primary">
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
            document.getElementById('confirmationModalConfirmButton').addEventListener('click', function (event) {
                window.submitConfirmationForm(event);
            })

            window.submitConfirmationForm = function (event) {
                let _originalHtml = event.target.innerHTML;
                let _processing = '<i class="fas fa-circle-notch fa-spin mr-2"></i>';
                event.target.innerHTML = _processing + event.target.innerHTML;
                event.target.disabled = true;

                window.app.confirmationFormTarget.closest('form').submit();

                let _modal = window.bootstrap.Modal.getOrCreateInstance(document.getElementById('confirmationModal'));
                _modal.toggle();
                event.target.innerHTML = _originalHtml;
                event.target.disabled = false;
            }

            window.app.confirmation = (_event) => {
                let _target = event.target;
                _event.preventDefault();

                if (! _target.hasAttribute('data-formsjs-confirm-message')) {
                    _target = _target.closest('button');
                }

                document.getElementById('confirmationModalMessage').innerHTML = _target.getAttribute('data-formsjs-confirm-message');
                let _modal = window.bootstrap.Modal.getOrCreateInstance(document.getElementById('confirmationModal'));
                _modal.toggle();
                window.app.confirmationFormTarget = _event.target;

                return false;
            };
        JS;
    }
}
