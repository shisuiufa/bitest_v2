<template>
    <app-header :dark="this.dark" @change-theme="changeTheme"/>
    <main class="main">
        <div class="container">
            <template v-if="$route.name === 'home'">
                <router-view></router-view>
            </template>
            <template v-else>
                <div class="row">
                    <div class="col-12">
                        <app-breadcrumbs></app-breadcrumbs>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="content">
                            <router-view></router-view>
                        </div>
                    </div>
                </div>
            </template>
        </div>
        <navbar-menu></navbar-menu>
    </main>
    <modal-search v-if="modalSearch"></modal-search>
</template>

<script>
import ModalSearch from "@/components/modals/ModalSearch.vue";
import AppHeader from "@/components/AppHeader.vue";
import NavbarMenu from "@/components/NavbarMenu.vue";
import {mapGetters} from "vuex";
import AppBreadcrumbs from "@/components/AppBreadcrumb.vue";

export default {
    name: "PublicLayout",
    components: {
        AppBreadcrumbs,
        AppHeader,
        ModalSearch,
        NavbarMenu
    },
    data(){
        return {
            dark: false,
            loader: false,
        }
    },
    computed:{
        ...mapGetters(['modalSearch', 'user']),
    },
    mounted() {
        const initUserTheme = this.getTheme() || this.getMediaPreference();
        this.dark = initUserTheme;
        this.setTheme(initUserTheme);
    },
    methods: {
        setTheme(theme) {
            if (theme === "dark") {
                this.dark = true;
                document.body.classList.add("dark");
            } else {
                this.dark = false;
                document.body.classList.remove("dark");
            }
            localStorage.setItem("theme-mode", theme);
        },
        changeTheme() {
            if (this.dark === true) {
                this.setTheme("light");
            } else {
                this.setTheme("dark");
            }
        },
        getMediaPreference() {
            const hasDarkPreference = window.matchMedia(
                "(prefers-color-scheme: dark)"
            ).matches;
            if (hasDarkPreference) {
                return "dark";
            } else {
                return "light";
            }
        },
        getTheme() {
            return localStorage.getItem("theme-mode");
        },
    },
}
</script>

<style lang="scss">
    .main {
        height: 100%;
        min-height: 100vh;
        padding-top: 55px;
        padding-bottom: 1rem;
        background-color: var(--body-bg);
    }
    .content {
        padding: 40px;
        border-radius: 15px;
        background-color: var(--body-bg-secondary);
        box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;
    }
</style>
