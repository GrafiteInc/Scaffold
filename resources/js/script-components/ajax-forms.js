/**
 * Handling Ajax form submissions
 */
window.ajax = (_event) => {
    let _originalContent = null;
    _event.preventDefault();

    let _form = _event.target.closest('form');
    let _button = _event.target;

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

                    if (! _field) { // means its a select... I guess
                        _fieldKeySelector = `select[name="${_fieldKey}"]`;
                        _field = document.querySelector(_fieldKeySelector);
                    }

                    if (! _field) { // means its a textarea... I guess
                        _fieldKeySelector = `textarea[name="${_fieldKey}"]`;
                        _field = document.querySelector(_fieldKeySelector);
                    }

                    if (! _field.classList.contains('is-invalid')) {
                        _field.classList.add('is-invalid');
                        _field.parentNode.appendChild(_errorMessage);
                    }

                    window.FormsJS_validation();
                });
            }

            if (_button && _originalContent) {
                _button.innerHTML = _originalContent;
            }
        });
    }
};

window.ajaxDebounced = window.app.debounce(window.ajax);

window.FormsJS_submit_debounce = window.app.debounce((event) => {
    if (event.target.form) {
        event.target.form.submit();
    }

    if (event.target.tagName === 'FORM') {
        event.target.submit();
    }
});

window.ajaxWithRefresh = function (event) {
    window.ajax(event);
    setTimeout(() => {
        window.Livewire.dispatch('refresh');
        setTimeout(() => {
            let _form = event.target.closest('form');

            if (_form) {
                _form.reset();
            }
        }, 1000);
    }, 1000);
};
