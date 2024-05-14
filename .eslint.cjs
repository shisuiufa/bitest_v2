module.exports = {
    root: true,
    parser: "vue-eslint-parser",
    parserOptions: {
        ecmaVersion: "latest",
        sourceType: "module",
        parser: "@typescript-eslint/parser",
    },
    plugins: ["vue", "@typescript-eslint"],
    extends: [
        "eslint:recommended",
        "plugin:vue/vue3-recommended",
        "prettier",
        "plugin:@typescript-eslint/recommended",
    ],
    rules: {
        "vue/require-default-prop": "off",
    },
};
