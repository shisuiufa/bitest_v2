<template>
    <div class="layout-wrapper d-flex align-items-stretch">
        <SidebarApp/>
        <main class="main">
            <app-header/>
            <div class="content w-100">
                <div class="container">
                    <template v-if="$route.name === 'home' || $route.name === 'admin'">
                        <router-view/>
                    </template>
                    <template v-else>
                        <div class="row">
                            <div class="col-12">
                                <app-breadcrumbs/>
                            </div>
                        </div>
                        <router-view/>
                    </template>
                </div>
            </div>
        </main>

        <modal-search v-if="this.modalSearch"/>
    </div>
</template>

<script>
import {mapGetters} from "vuex";
import ModalSearch from "@/components/modals/ModalSearch.vue";
import AppHeader from "@/components/nav/AppHeader.vue";
import AppBreadcrumbs from "@/components/nav/AppBreadcrumb.vue";
import SidebarApp from "@/components/nav/SidebarApp.vue";

export default {
    name: "PublicLayout",
    components: {
        AppBreadcrumbs,
        AppHeader,
        ModalSearch,
        SidebarApp
    },
    data() {
        return {
            loader: false,
            visible: true,
            collapsed: false,
        };
    },
    computed: {
        ...mapGetters(["modalSearch", "user"]),
    },
    methods: {
        toggleSidebar(){
            console.log('s')
            this.collapsed = !this.collapsed;
        }
    }
};
</script>

<style lang="scss">
.layout-wrapper {
    background-color: var(--surface-ground);
}
.main {
    width: 100%;
    height: 100%;
    min-height: 100vh;
    padding-top: 0;
    margin-top: 0;
}
</style>
