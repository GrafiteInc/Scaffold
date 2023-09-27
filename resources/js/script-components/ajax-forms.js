/**
 * Handling Ajax form submissions
 */
window.ajax = (_event) => {
    _event.preventDefault();

    let _form = _event.target.closest('form');
    let _button = _event.target;
    let _originalContent = null;

    if (! _button.hasAttribute('data-formsjs-onclick')) {
        _button = _button.closest('button');
    }

    if (_button) {
        _originalContent = _button.innerHTML;
        let _processing = '<i class="fas fa-circle-notch fa-spin bmx-fade-in"></i> ';
        _button.innerHTML = _processing + _originalContent;
    }

    if (_form) {
        let _method = _form.method.toLowerCase();
        let _data = new FormData(_form);

        window.axios[_method](_form.action, _data, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        }).then((response) => {
            let _modalElement = document.getElementById(`${_form.getAttribute('id')}_Modal`);

            if (_modalElement) {
                window.bootstrap.Modal.getOrCreateInstance(_modalElement).hide();
            }

            // Event handling
            let _event = `${_form.getAttribute('id')}.success`;
            window.app.$events.fire(_event, response.data.data);

            if (_button) {
                _button.innerHTML = _originalContent;
            }
        }).catch((error) => {
            // let _event = `${_form.getAttribute('id')}.error`;
            // window.app.$events.fire(_event, error.response.data);
            if (error.response && error.response.data) {
                [error.response.data.errors].forEach((key) => {
                    let _fieldKey = Object.keys(key)[0];
                    let _errorMessage = document.createElement('div');
                    _errorMessage.classList.add('invalid-feedback');
                    _errorMessage.innerText = error.response.data.errors[_fieldKey];

                    let _fieldKeySelector = `input[name="${_fieldKey}"]`;
                    let _field = document.querySelector(_fieldKeySelector);

                    if (! _field.classList.contains('is-invalid')) {
                        _field.classList.add('is-invalid');
                        _field.parentNode.appendChild(_errorMessage);
                    }

                    window.Forms_validation();
                });
            }

            if (_button) {
                _button.innerHTML = _originalContent;
            }
        });
    }
};

window.ajaxDebounced = window.app.debounce(window.ajax);

window.ajaxWithRefresh = function (event) {
    window.ajax(event);
    setTimeout(() => {
        window.Livewire.dispatch('refresh');
        setTimeout(() => {
            window.FormsJS();
            window.turnOnTooltips();
        }, 1000);
    }, 1000);
};
