<?php

namespace App\View\Components\Global;

use Grafite\Html\Tags\HtmlTagComponent;

class ActionModal extends HtmlTagComponent
{
    public static function template()
    {
        return <<<HTML
        <div class="modal fade" role="dialog" id="actionModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            Quick Action
                        </h5>
                        <button type="button" class="btn-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" class="form-control" onkeydown="window.submitActionForm(event)" />
                    </div>
                </div>
            </div>
        </div>
        HTML;
    }

    public static function js()
    {
        return <<<JS
            window.showQuickActions = function () {
                let _modal = window.bootstrap.Modal.getOrCreateInstance(document.getElementById('actionModal'));
                _modal.toggle();

                $('#actionModal').on('shown.bs.modal', function () {
                    $(this).find('input:visible:first').focus();
                });
            };

            window.addEventListener('keydown', (e) => {
                if (e.metaKey && e.code == 'Space') {
                    window.showQuickActions();
                }
            });

            window.submitActionForm = function (event) {
                if (event.code === 'Enter') {
                    axios.post(route('ajax.action'), {
                        text: event.target.value,
                    })
                    .then((results) => {
                        event.target.value = '';
                        let _modal = window.bootstrap.Modal.getOrCreateInstance(document.getElementById('actionModal'));
                        _modal.toggle();
                        window.notify.success('Action sent!');
                        window.app.\$events.fire('get-notifications');
                    })
                    .catch((err) => {
                        //
                    });
                }
            }
        JS;
    }
}
