import {createRouter, createWebHistory} from "vue-router";
import routes from "@/router/routes.js";
import store from "@/store/index.js";

const router = createRouter({
    history: createWebHistory(),
    linkActiveClass: 'active',
    routes: routes,
})

router.beforeEach((to, from, next) => {
    document.title = "BITEST " + to.meta.breadcrumb;

    if (to.meta.middleware === "guest") {
        if (store.state.auth.authenticated) {
            next({name: "home"})
        }
        next();
    } else {
        store.dispatch('checkAuth');

        if (store.state.auth.authenticated) {
            next()
        } else {
            next({name: "login"})
        }
    }
});

export default router;
