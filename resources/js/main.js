/* App */
import { createApp } from 'vue'
import App from '@/App.vue'

/* Vue Plugins */
import { createPinia } from 'pinia'
import router from '@/routes'
import vuetify from '@/plugins/vuetify'
import { vMaska } from 'maska/vue'

/* Components */
import FontAwesomeIcon from '@/font-awesome'
import NoContentFound from '@/components/NoContentFound.vue'

export default (() => {
    const app = createApp(App)

    /* Components */
    app.component('font-awesome-icon', FontAwesomeIcon)
    app.component('no-content-found', NoContentFound)

    /* Directives */
    app.directive('maska', vMaska)

    return app.use(router).use(vuetify).use(createPinia()).mount('#app')
})()