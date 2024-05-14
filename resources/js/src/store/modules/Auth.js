import router from "@/router/router.js";
import axios from "axios";

export default {
    actions: {
        checkAuth(ctx) {
            return axios
                .get("/api/user")
                .then((res) => {
                    ctx.commit("setUser", res.data);
                    ctx.commit("setAuthenticated", true);
                })
                .catch(() => {
                    ctx.dispatch("logout");
                });
        },
        login(ctx) {
            return axios
                .get("/api/user")
                .then((res) => {
                    ctx.commit("setUser", res.data);
                    ctx.commit("setAuthenticated", true);
                    router.push({ name: "home" });
                })
                .catch(() => {
                    ctx.dispatch("logout");
                });
        },
        logout(ctx) {
            ctx.commit("setUser", {});
            ctx.commit("setAuthenticated", false);
            axios.post("/logout");
        },
    },
    mutations: {
        setAuthenticated(state, value) {
            state.authenticated = value;
        },
        setUser(state, value) {
            state.user = value;
        },
    },
    state: {
        authenticated: false,
        user: {},
    },
    getters: {
        authenticated(state) {
            return state.authenticated;
        },
        user(state) {
            return state.user;
        },
    },
};
