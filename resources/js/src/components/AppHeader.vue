<template>
    <header class="header">
        <div class="container">
            <nav class="navbar p-0 justify-content-between">
                <div class="navbar__left d-flex">
                    <router-link class="navbar__brand navbar-brand" to="/">bitest</router-link>
                    <ul class="navbar__nav navbar-nav">
                        <li class="nav-item">
                            <router-link :to="{name: 'home'}" class="nav-link">Главная</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link :to="{name: 'created-tests'}" class="nav-link">Мои тесты</router-link>
                        </li>
                    </ul>
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
import {mapActions} from "vuex";
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
        ...mapActions(['toggleModal']),
    },
    emits: ['change-theme'],
}
</script>

<style scoped lang="scss">
    .header {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: var(--header-height);
        border-bottom: 1px solid var(--border-color);
        background-color: var(--body-bg);
        z-index: var(--index-header);
        &__search, &__theme {
            color: var(--main-color);
            font-size: 14px;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 35px;
            height: 35px;
            border-radius: 10px;
            background-color: var(--button-bg);
            transition: all .3s ease;
        }
    }
    .navbar {
        &__left {
            gap: 30px;
        }
        &__brand{
            color: var(--main-color);
            text-transform: uppercase;
            letter-spacing: 1.2px;
        }
        &__nav {
            display: flex;
            flex-direction: row;
            gap: 20px;
            font-size: 15px;
            .nav-link.active{
                opacity: 1;
            }
            .nav-link{
                opacity: 0.7;
                color: var(--main-color);
                font-weight: 500;
                &:hover{
                    opacity: 1;
                }
            }
        }
    }
</style>
