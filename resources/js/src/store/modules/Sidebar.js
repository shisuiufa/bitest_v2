export default {
    actions: {
        toggleSidebar(ctx) {
            ctx.commit("toggleSidebar");
        },
        closeSidebar(ctx) {
            ctx.commit("closeSidebar");
        },
        openSidebar(ctx) {
            ctx.commit("openSidebar");
        },
    },
    mutations: {
        toggleSidebar(state) {
            state.collapsed = !state.collapsed;
        },
        closeSidebar(state) {
            state.collapsed = false;
        },
        openSidebar(state) {
            state.collapsed = true;
        },
    },
    state: {
        collapsed: false,
    },
    getters: {
        stateSidebar(state) {
            return state.collapsed;
        },
    },
};
