<template>
    <router-view></router-view>
    <app-loader v-if="this.loader"></app-loader>
    <Toast position="bottom-right" />
</template>

<script>
import AppLoader from "@/components/AppLoader.vue";

export default {
    components: {
        AppLoader
    },
    data() {
        return {
            loader: false,
        }
    },
    created() {
        this.$router.beforeResolve((to, from, next) => {
            this.loader = true;
            next();
        })
        this.$router.afterEach((to, from) => {
            setTimeout(() => this.loader = false, 500);
        })
    }
}
</script>

<style lang="scss">
</style>
