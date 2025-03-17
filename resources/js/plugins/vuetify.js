import { createVuetify } from 'vuetify'
import { aliases, fa } from 'vuetify/iconsets/fa-svg'
import 'vuetify/styles'

export default createVuetify({
    theme: {
        defaultTheme: 'theme-light',
        themes: {
            'theme-light': {
                colors: {
                    primary: '#E65100',
                    success: '#26a69a'
                }
            }
        }
    },
    icons: {
        defaultSet: 'fa',
        aliases,
        sets: {
            fa
        }
    }
})
