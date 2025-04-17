/* App */
import { createApp } from 'vue'
import App from '@/App.vue'

/* Vue Plugins */
import { createPinia } from 'pinia'
import router from '@/routes'
import vuetify from '@/plugins/vuetify'
import '@vuepic/vue-datepicker/dist/main.css'

/* Components */
import FontAwesomeIcon from '@/font-awesome'
import VueDatePicker from '@vuepic/vue-datepicker';
import NoContentFound from '@/components/NoContentFound.vue'

/* Directives */
import { vMaska } from 'maska/vue'
import vCan from '@/directives/VCan'

export default (() => {
    const app = createApp(App)

    /* Components */
    app.component('font-awesome-icon', FontAwesomeIcon)
    app.component('vue-date-picker', VueDatePicker)
    app.component('no-content-found', NoContentFound)

    /* Directives */
    app.directive('maska', vMaska)
    app.directive('can', vCan)

    app.use(router).use(createPinia()).use(vuetify).mount('#app')
})()