window.Vue = require('vue');
window.axios = require('axios');

import Vuetify from 'vuetify'
import router from './router.js'
import VueSwal from 'vue-swal'
import FullScreen from 'vue-fullscreen'
import 'vuetify/dist/vuetify.min.css'
import * as VueGoogleMaps from 'vue2-google-maps'
import colors from 'vuetify/es5/util/colors'





//AIzaSyDhXPsyAaPIsqYdcbpWc-o45UXBJtbnlHM => api key berbayar (buat test aja)
Vue.use(VueGoogleMaps, {
  load: {
    key: 'AIzaSyDhXPsyAaPIsqYdcbpWc-o45UXBJtbnlHM',
  },

})


Vue.use(FullScreen);
Vue.use(VueSwal);

Vue.use(Vuetify, {
    iconfont: 'md',
    // override colors
    theme: {
    	menu:colors.blue.darken4,
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
