import "vue-class-component/hooks";
import Vue from "vue";
import router from "./router";
import store from "./store";
import "./bootstrap";
import App from "./components/App.vue";
import vuetify from "@/plugins/vuetify";

Vue.config.productionTip = false;

new Vue({
    router,
    store,
    vuetify,
    el: '#app',
    template: '<App/>',
    components: {
        App
    },
});
