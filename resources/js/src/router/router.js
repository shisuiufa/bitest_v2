import {createRouter, createWebHistory} from "vue-router";
import routes from "@/router/routes.js";
import store from "@/store/index.js";
import axios from "axios";

const router = createRouter({
    history: createWebHistory(),
    linkActiveClass: "active",
    routes: routes,
});

axios.interceptors.response.use(
    (response) => {
        return response;
    },
    (error) => {
        const status = error.response.status;
        if (status === 404) {
            router.push({name: "not-found"});
        }
        return Promise.reject(error);
    },
);

router.beforeEach((to, from, next) => {
    document.title = "CheckZone " + to.meta.breadcrumb;

    if (to.meta.middleware.includes("guest")) {
        if (store.state.auth.authenticated) {
            next({name: "home"});
        }
        next();
    } else {
        store.dispatch("checkAuth");

        if (store.state.auth.authenticated) {
            const userRoles = store.state.auth.user.roles.map(role => role.slug);

            const containsAllRoles = userRoles.some(role => to.meta.middleware.includes(role))

            if (!containsAllRoles) {
                next({name: "not-found"});
            }

            next();
        } else {
            next({name: "login"});
        }
    }
});

export default router;
