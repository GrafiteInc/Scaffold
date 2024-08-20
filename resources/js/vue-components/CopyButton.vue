<template>
  <div>
    <button
      class="btn btn-outline-primary"
      @click="success"
    >
      <slot />
    </button>
  </div>
</template>

<script>
export default {
    props: {
        message: {
            type: String
        }
    },
    data () {
        return {
            copyData: this.message
        };
    },
    methods: {
        success: () => {
            window.app.notify.success("Copied!");
        }
    }
};
</script>
