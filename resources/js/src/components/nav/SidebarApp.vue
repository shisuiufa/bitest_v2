<template>
    <div class="sidebar" :class="this.stateSidebar ? 'collapsed' : ''">
        <div class="sidebar__content">
            <div class="sidebar__navbar">
                <router-link class="sidebar__brand text-center d-block my-4" to="/">
                    <p>
                        Check<span>Zone</span>
                    </p>
                </router-link>
                <ul class="navbar-nav">
                    <template v-for="link in links">
                        <li class="nav-item " v-if="this.hasRole(link.role)">
                            <router-link class="nav-link d-flex gap-2 py-3 px-4 rounded" :to="link.route">
                                <i :class="link.icon"></i>
                                <span>{{link.name}}</span>
                            </router-link>
                        </li>
                    </template>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
import {Role} from "@/models/user.ts";
import {mapGetters} from "vuex";
export default {
    name: "SidebarApp",
    data() {
        return {
            links: [
                {
                    name: 'Главная',
                    icon: 'bi bi-house-door',
                    route: { name: 'home' },
                    role: [Role.User]
                },
                {
                    name: 'Мои тесты',
                    icon: 'bi bi-ui-checks-grid',
                    route: { name: 'created-tests' },
                    role: [Role.Admin, Role.Moderator]
                },
                {
                    name: 'Результаты',
                    icon: 'bi bi-card-checklist',
                    route: { name: 'all-result' },
                    role: [Role.User]
                },
                {
                    name: 'Настройки',
                    icon: 'bi bi-sliders',
                    route: { name: 'admin' },
                    role: [Role.Admin]
                }
            ]
        }
    },
    methods: {
        hasRole(roles) {
            const userRoles = this.user.roles.map(role => role.slug);
            return userRoles.some(role => roles.includes(role))
        },
    },
    computed: {
        Role() {
            return Role
        },
        ...mapGetters(["user", "stateSidebar"]),
    },
}
</script>

<style scoped lang="scss">
.sidebar {
    width: 300px;
    background-color: var(--surface-card);
    border-right: 1px solid var(--surface-border);
    color: #fff;
    transition: all .2s ease;

    &__content {
        position: sticky;
        height: 100%;
        top: 0;
        left: 0;
        padding: 20px;
    }
    &__brand {
        color: var(--surface-900);
        text-transform: uppercase;
        letter-spacing: 1.2px;
        font-weight: 900;
        font-size: 25px;
        span {
            color: var(--primary-color)
        }
    }
}
.collapsed {
    margin-left: -300px;
}
.nav-item {
    border-bottom: 1px solid var(--surface-border);
    margin-bottom: 5px;
    &:last-child {
        border:none;
    }
}

.nav-link {
    font-weight: 900;
    font-size: 16px;
    transition: all .2s ease;

    &:hover {
        color: #fff !important;
        background-color: var(--primary-color);
    }
}
.nav-link, .nav-link.active {
    color: var(--surface-900);
}
.router-link-exact-active {
    color: #fff !important;
    background-color: var(--primary-color);
}
</style>
