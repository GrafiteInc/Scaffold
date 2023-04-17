<?php

namespace App\View\Components\Global;

use Grafite\Html\Tags\HtmlTagComponent;

class Notifications extends HtmlTagComponent
{
    public static function template()
    {
        return <<<HTML
            <div class="position-fixed top-0 end-0 pe-3 pt-4 mt-5 bmx-z-4">
                <div id="toaster" class="toast border-0 align-items-center" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex text-white">
                        <div id="_componentNotification" class="toast-body"></div>
                        <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            </div>
        HTML;
    }

    public static function js()
    {
        return <<<JS
                let _this = this;

                window.notify = {
                    removeClassByPrefix: function (el, prefix) {
                        let newClassList = [];

                        el.classList.forEach((className) => {
                            if (className.indexOf(prefix) !== 0) {
                                newClassList.push(className);
                            }
                        });

                        el.className = newClassList.join(' ');
                    },
                    success: function (message, delay = 2000) {
                        this.notify(message, 'bg-success', 'Success', delay);
                    },
                    info: function (message, delay = 2000) {
                        this.notify(message, 'bg-info', 'Info', delay);
                    },
                    warning: function (message, delay = 2000) {
                        this.notify(message, 'bg-warning', 'Warning', delay);
                    },
                    error: function (message, delay = 2000) {
                        this.notify(message, 'bg-danger', 'Error', delay);
                    },
                    notify: function (message, variant, title, delay = 2000) {
                        let _toaster = document.getElementById('toaster');
                        window.notify.removeClassByPrefix(_toaster, 'bg-');
                        _toaster.classList.add(variant);
                        document.getElementById('_componentNotification').innerHTML = message;

                        let _toast = new window.bootstrap.Toast(document.getElementById('toaster'), {
                            delay: delay
                        });

                        _toast.show();
                    }
                };

                if (window.session.message) {
                    window.notify.success(window.session.message);
                }

                if (window.session.info) {
                    window.notify.info(window.session.info);
                }

                if (window.session.warning) {
                    window.notify.warning(window.session.warning);
                }

                if (window.session.error) {
                    window.notify.error(window.session.error);
                }
        JS;
    }
}
