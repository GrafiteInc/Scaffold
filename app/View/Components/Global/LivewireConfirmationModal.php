<?php

namespace App\View\Components\Global;

use Grafite\Html\Tags\HtmlTagComponent;

class LivewireConfirmationModal extends HtmlTagComponent
{
    public static function template()
    {
        return <<<HTML
            <div class="modal fade" role="dialog" id="livewireConfirmationModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                Confirmation
                            </h5>
                            <button type="button" class="btn-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p class="mb-4" id="livewireConfirmationModalMessage"></p>
                            <button onclick="window.submitLivewireConfirmationForm(event)" class="float-end btn btn-outline-primary">
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
            window.submitLivewireConfirmationForm = function (event) {
                let _livewireMethod = window.livewireConfirmationFormTarget.closest('form').getAttribute('wire:submit.prevent');
                let _id = _livewireMethod.replace(/^\D+/g, '');
                window.Livewire.emit('formConfirmed', _id);

                let _modal = window.bootstrap.Modal.getOrCreateInstance(document.getElementById('livewireConfirmationModal'));
                _modal.toggle();
            }

            window.livewireConfirmation = (_event, _message) => {
                _event.preventDefault();

                document.getElementById('livewireConfirmationModalMessage').innerHTML = _message;
                let _modal = window.bootstrap.Modal.getOrCreateInstance(document.getElementById('livewireConfirmationModal'));
                _modal.toggle();
                window.livewireConfirmationFormTarget = _event.target;

                return false;
            };
        JS;
    }
}
