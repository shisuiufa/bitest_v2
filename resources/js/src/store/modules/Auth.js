import router from "@/router/router.js";
import axios from "axios";

export default {
    actions: {
        async checkAuth(ctx) {
            return await axios
                .get("/api/user")
                .then((res) => {
                    ctx.commit("setUser", res.data.data);
                    ctx.commit("setAuthenticated", true);
                })
                .catch(() => {
                    ctx.dispatch("logout");
                });
        },
        async login(ctx) {
            return await axios
                .get("/api/user")
                .then((res) => {
                    ctx.commit("setUser", res.data.data);
                    ctx.commit("setAuthenticated", true);
                    router.push({ name: "home" });
                })
                .catch(() => {
                    ctx.dispatch("logout");
                });
        },
        async logout(ctx) {
            ctx.commit("setUser", {});
            ctx.commit("setAuthenticated", false);
            await axios.post("/logout");
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
