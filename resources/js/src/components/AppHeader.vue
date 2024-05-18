<template>
    <header class="header">
        <div class="container">
            <nav class="navbar p-0 justify-content-between">
                <div class="navbar__left d-flex">
                    <router-link class="navbar__brand navbar-brand" to="/">
                        Bitest
                    </router-link>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <div class="header__theme" @click="$emit('change-theme')">
                        <i v-if="dark" class="bi bi-brightness-high"></i>
                        <i v-else class="bi bi-moon"></i>
                    </div>
                    <div @click="toggleModal('search')" class="header__search">
                        <i class="bi bi-search"></i>
                    </div>
                    <profile-nav></profile-nav>
                </div>
            </nav>
        </div>
    </header>
</template>

<script>
import ProfileNav from "@/components/ProfileNav.vue";
import { mapActions } from "vuex";
export default {
    name: "AppHeader",
    components: {
        ProfileNav,
    },
    props: {
        dark: {
            type: Boolean,
            required: true,
        },
    },
    methods: {
        ...mapActions(["toggleModal"]),
    },
    emits: ["change-theme"],
};
</script>

<style scoped lang="scss">
.header {
    position: fixed;
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
    }
    &__nav {
        display: flex;
        flex-direction: row;
        gap: 20px;
        font-size: 15px;
        .nav-link.active {
            opacity: 1;
        }
        .nav-link {
            opacity: 0.7;
            color: var(--main-color);
            font-weight: 500;
            &:hover {
                opacity: 1;
            }
        }
    }
}
</style>
