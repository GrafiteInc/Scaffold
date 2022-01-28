<template>
<div id="offCanvas" class="offcanvas offcanvas-end" ref="offcanvas-panel" tabindex="-1" aria-labelledby="offcanvasRightLabel">
  <div class="offcanvas-header">
    <h5 id="offcanvasRightLabel" v-html="title"></h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <span v-html="content"></span>
  </div>
</div>
</template>

<script>
export default {
    mame: 'offcanvas',
    data () {
        return {
            title: '',
            content: ''
        };
    },
    created () {
        window.offCanvas = (_title, _message) => {
            this.title = _title;
            this.content = _message;

            let _offcanvas = bootstrap.Offcanvas.getOrCreateInstance(this.$refs['offcanvas-panel']);
                _offcanvas.toggle();
        }
    }
};
</script>
