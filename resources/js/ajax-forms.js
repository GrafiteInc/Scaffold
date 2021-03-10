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
    })
    .then((response) => {
        window.notify.success(response.data.message);
    })
    .catch((error) => {
        window.notify.warning(error.response.data.message);

        for (var key in error.response.data.errors) {
            document.querySelector('input[name="'+key+'"]').classList.add('border-danger');
            window.notify.error(error.response.data.errors[key][0]);
        }
    });
}
