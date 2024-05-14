<template>
    <app-header :dark="this.dark" @change-theme="changeTheme" />
    <main class="main">
        <div class="container">
            <template v-if="$route.name === 'home'">
                <router-view />
            </template>
            <template v-else>
                <div class="row">
                    <div class="col-12">
                        <app-breadcrumbs />
                    </div>
                </div>
                <router-view />
            </template>
        </div>
        <navbar-menu />
    </main>
    <modal-search v-if="this.modalSearch" />
</template>

<script>
import { mapGetters } from "vuex";
import ModalSearch from "@/components/modals/ModalSearch.vue";
import AppHeader from "@/components/AppHeader.vue";
import NavbarMenu from "@/components/NavbarMenu.vue";
import AppBreadcrumbs from "@/components/AppBreadcrumb.vue";

export default {
    name: "PublicLayout",
    components: {
        AppBreadcrumbs,
        AppHeader,
        ModalSearch,
        NavbarMenu,
    },
    data() {
        return {
            dark: false,
            loader: false,
        };
    },
    computed: {
        ...mapGetters(["modalSearch", "user"]),
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

                // document.body.classList.add("dark");
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
                "(prefers-color-scheme: dark)",
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
};
</script>

<style lang="scss">
.v-enter-active,
.v-leave-active {
    transition: opacity 0.5s ease;
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
}
.main {
    height: 100%;
    min-height: 100vh;
    padding-top: 55px;
    padding-bottom: 1rem;
    background-color: var(--body-bg);
}
</style>
