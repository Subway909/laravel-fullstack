import Vue from 'vue';
import Vuetify from 'vuetify/lib/framework';
import pt from 'vuetify/es5/locale/pt'

Vue.use(Vuetify);

const opts = {
    lang: {
        locales: {pt},
        current: 'pt'
    }
}

export default new Vuetify(opts)
