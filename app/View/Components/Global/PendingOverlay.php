<?php

namespace App\View\Components\Global;

use Grafite\Html\Tags\HtmlTagComponent;

class PendingOverlay extends HtmlTagComponent
{
    public static function template()
    {
        return <<<HTML
            <div id="_componentPendingOverlay" class="overlay d-none bg-dark">
                <div class="spinner-container">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        HTML;
    }

    public static function styles()
    {
        return <<<CSS
            .overlay {
                width: 100%;
                height: 100vh;
                position: absolute;
                top: 0;
                left: 0;
                z-index: 30000;
                opacity: .8;
            }
            .spinner-container {
                position: absolute;
                top: 49%;
                left: calc(50% - 15px);
            }
        CSS;
    }

    public static function js()
    {
        //
        // $position = <<<JS
        //     document.addEventListener('DOMContentLoaded', function () {
        //         window.scrollTo({
        //             top: window.localStorage.getItem('position'),
        //             left: 0,
        //             behavior: 'instant'
        //         });
        //     })
        // JS;

        // if (! session('positioned')) {
        //     $position = '';
        // }

        return <<<JS
            window.pending = (button) => {
                if (button && button.form.checkValidity()) {
                    button.form.submit();
                    button.disabled = true;
                    document.getElementById('_componentPendingOverlay').classList.remove('d-none');
                }

                if (! button) {
                    document.getElementById('_componentPendingOverlay').classList.remove('d-none');
                }

                return false;
            };

            window.pendingHide = () => {
                document.getElementById('_componentPendingOverlay').classList.add('d-none');
            };

            // window.addEventListener("scroll", function () {
            //     window.localStorage.setItem('position', window.pageYOffset)
            // });

            // position
        JS;
    }
}
