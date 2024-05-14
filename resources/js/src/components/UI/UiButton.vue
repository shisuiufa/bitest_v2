<template>
    <div class="btn ui-button">
        <slot></slot>
    </div>
</template>

<script>
export default {
    name: "UiButton",
};
</script>

<style scoped lang="scss">
.ui-button {
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
