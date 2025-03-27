import { h } from 'vue'
import { VIcon } from 'vuetify/components/VIcon'

export default function useIcon()  {
    function icon(name) {
        return () => h(VIcon, { icon: name, size: 'x-small' })
    }

    return {
        icon
    }
}