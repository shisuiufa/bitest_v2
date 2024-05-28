export default {
    actions: {
        toggleSidebar(ctx) {
            ctx.commit("toggleSidebar");
        },
    },
    mutations: {
        toggleSidebar(state) {
            state.collapsed = !state.collapsed;
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
