<template>
    <router-view></router-view>
    <ScrollTop target="parent"
               :threshold="100"
               icon="pi pi-arrow-up"
    />
</template>

<script>
import ScrollTop from 'primevue/scrolltop';
export default {
    name: "CreatedTestLayout",
};
</script>

<style scoped lang="scss"></style>
