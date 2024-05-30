<template>
    <header class="header">
        <div class="container">
            <nav class="navbar p-0 align-items-center justify-content-between">
                <div class="navbar__left">
                  <Button @click="this.toggleSidebar" icon="pi pi-bars" aria-label="Submit" class="rounded" rounded />
                </div>
                <div class="d-flex align-items-center gap-2">
                    <div class="header__theme" @click="this.changeTheme()">
                        <i v-if="dark" class="bi bi-brightness-high"></i>
                        <i v-else class="bi bi-moon"></i>
                    </div>
                    <profile-nav></profile-nav>
                </div>
            </nav>
        </div>
    </header>
</template>

<script>
import ProfileNav from "@/components/nav/ProfileNav.vue";
import {mapActions} from "vuex";

export default {
    name: "AppHeader",
    components: {
        ProfileNav,
    },
    data() {
        return {
            dark: false,
            sidebar: true,
        }
    },
    methods: {
        ...mapActions(["toggleModal", "toggleSidebar"]),
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
                this.setTheme('light');
            } else {
                this.setTheme('dark');
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
    mounted() {
        const initUserTheme = this.getTheme() || this.getMediaPreference();
        this.dark = initUserTheme;
        this.setTheme(initUserTheme);
    },
    emits: ['toggle-sidebar'],
};
</script>

<style scoped lang="scss">
.header {
    position: sticky;
    top: 0;
    left: 0;
    width: 100%;
    height: var(--header-height);
    box-shadow: 0 3px 5px #00000005, 0 0 2px #0000000d, 0 1px 4px #00000014;
    backdrop-filter: blur(8px);
    background-color: var(--topbar-sticky-bg);
    z-index: var(--index-header);

    &__search,
    &__theme {
        color: var(--text-color-secondary);
        font-size: 16px;
        cursor: pointer;
        display: flex;
        justify-content: center;
        align-items: center;
        width: 35px;
        height: 35px;
        padding: 10px;
        border-radius: 10px;
        transition: all 0.3s ease;

        &:hover {
            background-color: var(--surface-hover);
        }
    }
}

.navbar {
    &__left {
        gap: 30px;
    }

    &__brand {
        color: var(--surface-900);
        text-transform: uppercase;
        letter-spacing: 1.2px;
        font-weight: 900;
        font-size: 25px;
        span {
            color: var(--primary-color);
        }
    }
}

</style>
