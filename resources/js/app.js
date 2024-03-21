import './bootstrap';
import {createApp} from "vue";
import App from '@/App.vue';
import Router from "@/router/router.js";
import Store from "@/store/index.js";

createApp(App)
    .use(Router)
    .use(Store)
    .mount('#app');
