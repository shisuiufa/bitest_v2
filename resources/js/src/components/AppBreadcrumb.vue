<template>
    <Breadcrumb :model="breadcrumbs" class="my-3 fw-semibold">
        <template #item="{ item }">
            <router-link
                v-if="item.route"
                v-slot="{ href, navigate }"
                :to="item.route"
                class="text-decoration-none p-menuitem-link"
            >
                <span class="p-menuitem-text">{{ item.label }}</span>
            </router-link>
        </template>
    </Breadcrumb>
</template>

<script>
export default {
    name: "AppBreadcrumb",
    components: {
        data() {
            return {
                home: {
                    icon: "pi pi-home",
                    route: "/home",
                },
            };
        },
    },
    computed: {
        breadcrumbs() {
            return this.$route.matched
                .map((route) => {
                    const breadcrumbText = route.meta.breadcrumb;
                    if (breadcrumbText) {
                        return {
                            label: breadcrumbText,
                            route: route,
                        };
                    } else {
                        return null;
                    }
                })
                .filter((item) => item !== null);
        },
    },
};
</script>

<style lang="scss"></style>
