<template>
    <div class="position-fixed top-0 end-0 pe-3 pt-4 mt-5">
        <div id="toaster" class="toast align-items-center" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" v-html="message"></div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    mame: 'notifications',
    data () {
        return {
            message: ''
        }
    },
    mounted () {
        let _this = this;

        window.notify = {
            success: function (message, delay = 2000) {
                this.notify(message, 'success', 'Success', delay);
            },
            info: function (message, delay = 2000) {
                this.notify(message, 'info', 'Info', delay);
            },
            warning: function (message, delay = 2000) {
                this.notify(message, 'warning', 'Warning', delay);
            },
            error: function (message, delay = 2000) {
                this.notify(message, 'danger', 'Error', delay);
            },
            notify: function (message, variant, title, delay = 2000) {
                let _toaster = document.getElementById('toaster');
                _this.removeClassByPrefix(_toaster, 'bg-');
                _toaster.classList.add('bg-' + variant);
                _this.message = message;

                let _toast = new bootstrap.Toast(document.getElementById('toaster'), {
                    delay: delay
                });

                _toast.show()
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
    },
    methods: {
        removeClassByPrefix (el, prefix) {
            let newClassList = []

            el.classList.forEach(className => {
                if (className.indexOf(prefix) !== 0 ) {
                    newClassList.push(className)
                }
            })

            el.className = newClassList.join(' ')
        }
    }
};
</script>
