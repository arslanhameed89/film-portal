import 'babel-polyfill'
import Vue from 'vue'
import _ from 'lodash'
import router from '~/router/index'
import store from '~/store/index'
import App from '$comp/App'
import '~/plugins/index'
import vuetify from '~/plugins/vuetify'
import axios from 'axios'
import { api } from '~/config'
import axiosRequest from '~/plugins/axiosRequest';

window.axios = axios;
window.api = api;
window._ = _;
window.axiosRequest = axiosRequest;
window.moment = require('moment');

/*use packages*/
Vue.use(require('vue-moment'), {
    moment
});

/*define constants*/
const moment = require('moment');

/*initialize VUE instance*/
export const app = new Vue({
  router,
  store,
  vuetify,
  render: h => h(App)
}).$mount('#app')
