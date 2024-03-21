import { createStore } from "vuex"
import auth from "@/store/modules/Auth.js";
import modal from "@/store/modules/Modal.js";
import testPass from "@/store/modules/TestPass.js";
import createTest from "@/store/modules/CreateTest.js";
import createPersistedState from 'vuex-persistedstate';

const store = createStore({
    plugins:[
        createPersistedState({paths: ['auth', 'createTest', 'testPass']})
    ],
    modules: {
        auth,
        modal,
        testPass,
        createTest,
    }
})

export default store;
