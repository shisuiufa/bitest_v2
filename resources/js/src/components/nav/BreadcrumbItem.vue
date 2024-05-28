<template>
    <li class="breadcrumbs__item">
        <router-link class="breadcrumbs__link" :to="item.to">{{
            item.text
        }}</router-link>
        <span v-if="!last">/</span>
    </li>
</template>

<script>
export default {
    name: "BreadcrumbItem",
    props: {
        item: Object,
        last: Boolean,
    },
};
</script>

<style lang="scss"></style>
