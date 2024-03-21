<template>
        <label v-if="label" :for="id" class="form-label">{{label}}</label>
        <input :tabindex="tabindex"
               :value="modelValue"
               @input="$emit('update:model-value', $event.target.value)"
               :type="type"
               class="input form-control"
               :id="id">
</template>

<script>
    export default {
        name: "CustomInput",
        props: {
            label: {
                type: String,
                required: false,
            },
            type: {
                type: String,
                required: true,
            },
            id: {
                type: String,
                required: false,
            },
            modelValue: {
                type: [String, Number],
                default: '',
            },
            tabindex: {
                type: Number,
                required: false,
                default: 0,
            },
        },
        emits: ["update:model-value"],
    }
</script>

<style scoped lang="scss">
    .input {
        font-size: 14px;
        font-weight: 600;
        color: var(--main-color);
        background-color: var(--body-bg);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        &:hover, &:focus {
            border: 1px solid var( --border-color-two);
            box-shadow: none;
        }
    }
</style>
