export default {
    actions: {
        toggleModal(ctx, modal) {
            ctx.commit('toggle', modal)
        }
    },
    mutations: {
        toggle (state, modal) {
           if (modal === "search") {
               state.modalSearch = !state.modalSearch;
           }
           if (modal === "profile"){
               state.modalProfile = !state.modalProfile;
           }
        },
    },
    state: {
        modalProfile: false,
        modalSearch: false,
    },
    getters: {
        modalSearch(state) {
            return state.modalSearch;
        },
        modalProfile(state) {
            return state.modalProfile;
        },
    },
}
