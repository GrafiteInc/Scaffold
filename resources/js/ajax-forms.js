/**
 * This is an example of how we can handle ajax based form submissions.
 */
window.ajax = (_event) => {
    _event.preventDefault();

    let _form = _event.target.form;
    let _method = _form.method.toLowerCase();
    let _data = new FormData(_form);

    window.axios[_method](_form.action, _data, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    }).then((response) => {
        let _event = `${_form.getAttribute('id')}.success`;
        window.app.$event.fire(_event, response.data.data);
        window.notify.success(response.data.message);
    }).catch((error) => {
        let _event = `${_form.getAttribute('id')}.error`;
        window.app.$event.fire(_event, error.response.data.data);
        window.notify.warning(error.response.data.message);

        [...error.response.data.errors].forEach((key) => {
            let _errorMessage = document.createElement('div');
            _errorMessage.classList.add('invalid-feedback');
            _errorMessage.innerText = error.response.data.errors[key][0];

            let _field = document.querySelector(`input[name="${key}"]`);
            _field.classList.add('is-invalid');
            _field.parentNode.appendChild(_errorMessage);

            window.Forms_validation();

            window.notify.error(error.response.data.errors[key][0]);
        });
    });
};
