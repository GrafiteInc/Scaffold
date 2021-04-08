module.exports = {
    extends: [
        "airbnb-base",
        "plugin:vue/recommended"
    ],
    parserOptions: {
        ecmaVersion: 2018
    },
    env: {
        browser: true,
        node: true,
        es6: true
    },
    globals: {
        App: true,
        axios: true,
        collect: true,
        Vue: true,
        moment: true,
        _: true,
        $: true,
        FormErrors: true,
        store: true,
        jQuery: true,
        route: true,
        trans: true,
        swal: true,
        s: true,
        jest: true,
        describe: true,
        it: true,
        afterEach: true,
        afterAll: true,
        beforeEach: true,
        beforeAll: true,
        test: true,
        expect: true,
        xit: true,
        xdescribe: true,
        i18n: true,
        UrlParser: true,
        $alerts: true,
        beeAuth: true,
        chrome: true
    },
    rules: {
        "arrow-body-style": [
            "error",
            "always"
        ],
        "class-methods-use-this": "off",
        "comma-dangle": "off",
        "func-names": [
            "error",
            "never"
        ],
        indent: [
            "error",
            4
        ],
        "max-len": "off",
        quotes: [
            "error",
            "single",
            {
                allowTemplateLiterals: true
            }
        ],
        "space-before-function-paren": [
            "error",
            "always"
        ],
        "vue/html-closing-bracket-newline": [
            "error",
            {
                singleline: "never",
                multiline: "always"
            }
        ],
        "vue/html-indent": [
            "error",
            4
        ],
        "vue/html-self-closing": [
            "error",
            {
                html: {
                    void: "never",
                    normal: "never",
                    component: "never"
                },
                svg: "always",
                math: "always"
            }
        ],
        "vue/max-attributes-per-line": [
            "error",
            {
                singleline: 3,
                multiline: {
                    max: 1,
                    allowFirstLine: false
                }
            }
        ],
        "vue/no-unused-components": "off",
        "vue/no-v-html": "off",
        "vue/require-default-prop": "off",
        "vue/require-prop-types": "off",
        "import/no-unresolved": "off",
        "no-plusplus": "off",
        "prefer-const": "off",
        "prefer-destructuring": "off",
        eqeqeq: "off",
        "no-underscore-dangle": "off",
        "no-console": "off",
        "consistent-return": "off",
        "no-unused-vars": "off",
        "no-shadow": "off",
        "no-param-reassign": "off",
        "no-multi-assign": "off",
        "no-new": "off",
        "import/prefer-default-export": "off",
        "vue/order-in-components": "off",
        "vue/attributes-order": "off",
        "array-callback-return": "off",
        radix: "off",
        "no-nested-ternary": "off",
        "new-cap": "off",
        "space-unary-ops": "off",
        "vue/max-attributes-per-line": "off",
        "object-shorthand": "off",
        "import/newline-after-import": "off",
        "import/first": "off",
        "import/extensions": "off",
        "no-use-before-define": "off",
        "import/no-extraneous-dependencies": "off",
        "camelcase": [
            "error",
            {
                "ignoreDestructuring": true,
                "properties": "never"
            }
        ]
    }
};
