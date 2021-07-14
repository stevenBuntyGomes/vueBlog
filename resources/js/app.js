require('./bootstrap');
window.Vue = require('vue');


import router from './router.js'
import common from './common.js'
import store from './store.js'
import ViewUI from 'view-design'
import 'view-design/dist/styles/iview.css'
import locale from  'view-design/dist/locale/en-US'
Vue.use(ViewUI);

Vue.mixin(common);

import Editor from 'vue-editor-js'

Vue.use(Editor)



Vue.component('mainapp', require('./components/mainapp.vue').default);
const App = new Vue({
    el: '#app',
    router,
    store,
    common
});
