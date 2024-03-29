<script>
export default {
    name: 'ButtonLink',
    props: {
        to: {
            type: Object,
            required: true,
        }
    }
}
</script>

<template>
    <router-link :to="to"
                 class="btn">
        <slot></slot>
    </router-link>
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
