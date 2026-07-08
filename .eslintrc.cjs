const vue = require("eslint-plugin-vue");

module.exports = [
    ...vue.configs["flat/recommended"],
    {
        files: ["resources/js/**/*.{js,vue}"],
        rules: {
            "no-undef": 0,
            "vue/multi-word-component-names": 0,
            "vue/no-v-html": 0,
            "vue/require-default-prop": 0,
            "vue/no-setup-props-destructure": 0,
            indent: ["error", 4],
            quotes: ["error", "double"],
            "object-curly-spacing": ["error", "always"],
            semi: ["error", "always"],
            "comma-spacing": ["error", { before: false, after: true }],
        },
    },
];