window.Vue = require('vue');
window.axios = require('axios');

import Vuetify from 'vuetify'
import router from './router.js'
import VueSwal from 'vue-swal'
import FullScreen from 'vue-fullscreen'
import 'vuetify/dist/vuetify.min.css'

Vue.use(FullScreen);
Vue.use(VueSwal);

Vue.use(Vuetify, {
    iconfont: 'md',
    // override colors
    theme: {
    }
});

import DilogApp from './DilogApp'
const app = new Vue({
    el: '#app',
    router,
    components: {
        DilogApp,
    },
});
