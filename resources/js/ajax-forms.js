/**
 * This is an example of how we can handle ajax based form submissions.
 */
window.ajax = (_event) => {
    _event.preventDefault();

    let _form = _event.target.parentNode.parentNode.parentNode;
    let _method = _form.method.toLowerCase();
    let _payloadArray = $(_form).serializeArray();
    let _payload = {};

    $.map(_payloadArray, function(n, i){
        _payload[n['name']] = n['value'];
    });

    window.axios[_method](_form.action, _payload)
        .then((response) => {
            window.snotify.success(response.data.message);
        })
        .catch((error) => {
            window.snotify.warning(error.response.data.message);

            for (var key in error.response.data.errors) {
                $('input[name="'+key+'"]').addClass('border-danger');
                window.snotify.error(error.response.data.errors[key][0]);
            }
        });
}
