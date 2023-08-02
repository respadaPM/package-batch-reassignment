import Vue from "vue";
import BootstrapVue from "bootstrap-vue";
import VModal from "vue-js-modal";
import SampleListing from "./components/SamplesListing";

Vue.use(VModal);
Vue.use(BootstrapVue);

new Vue({
    el: "#app-package-batch-reassignment",
    data: {
        filter: ""
    },
    components: {SampleListing},
});
