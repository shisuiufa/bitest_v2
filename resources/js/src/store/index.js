import { createStore } from "vuex";
import auth from "@/store/modules/Auth.js";
import modal from "@/store/modules/Modal.js";
import testPass from "@/store/modules/TestPass.js";
import createTest from "@/store/modules/CreateTest.js";
import sidebar from "@/store/modules/Sidebar.js";
import createPersistedState from "vuex-persistedstate";

const store = createStore({
    plugins: [
        createPersistedState({ paths: ["auth", "createTest", "sidebar"] }),
    ],
    modules: {
        auth,
        modal,
        testPass,
        createTest,
        sidebar,
    },
});

export default store;
