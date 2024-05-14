<script>
export default {
    name: "ButtonSpinner",
};
</script>

<template>
    <button class="btn btn-primary" type="button" disabled>
        <span
            class="spinner-border spinner-border-sm"
            role="status"
            aria-hidden="true"
        ></span>
        <slot></slot>
    </button>
</template>

<style scoped lang="scss">
.btn {
    color: #fff;
    background-color: var(--brand-color);
    border-color: var(--brand-color);
    transition: all 0.2s ease;

    &:active {
        color: #fff;
        border-color: transparent;
    }

    &:hover {
        background-color: #000;
    }

    &_disabled {
        opacity: 0.5;
        cursor: default;
        background-color: #000;

        &:hover {
            background-color: #000;
        }
    }
}
</style>
