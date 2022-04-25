import "./bootstrap";
import Vue from "vue";
import vuetify from "@/js/plugins/vuetify.js";
import App from "@/js/views/App";
import store from "@/js/store";
import router from "@/js/router";

const app = new Vue({
    el: "#app",
    vuetify,
    store: store,
    router: router,
    render: (h) => h(App),
});

export default app;
