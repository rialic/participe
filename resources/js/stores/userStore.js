import { ref, watch } from 'vue'
import { defineStore } from 'pinia'
import axios from '@/configs/axios'

export const useUserStore = defineStore('userStore', () => {
    const UserStore = {}
    UserStore.mount = function() {
        if (localStorage.getItem('user')) {
            state.value.user = JSON.parse(localStorage.getItem('user'))
        }

        if (localStorage.getItem('userAuthenticated')) {
            state.value.userAuthenticated = JSON.parse(localStorage.getItem('userAuthenticated'))
        }
    }

    const state = ref({
        user: {}
    })

    const me = async () => {
        return await axios.get('v1/user/me')
    }

    watch([() => state.value.userAuthenticated, () => state.value.user], ([userAuthenticated, user]) => {
        localStorage.setItem('userAuthenticated', userAuthenticated)
        localStorage.setItem('user', JSON.stringify(user))
    }, { deep: true })


    UserStore.mount()

    return {
        state,
        me
    }
})