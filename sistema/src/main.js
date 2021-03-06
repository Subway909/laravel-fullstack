import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import vuetify from './plugins/vuetify'
import VueTheMask from 'vue-the-mask'
import moment from 'moment'
import './plugins/session'
import './plugins/http'
import VueSimpleAlert from "vue-simple-alert";

Vue.config.productionTip = false

moment.locale('pt-BR')

Vue.use(VueTheMask)
Vue.use(VueSimpleAlert);

new Vue({
  router,
  store,
  vuetify,
  render: h => h(App)
}).$mount('#app')
