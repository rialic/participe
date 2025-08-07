import { defineStore } from 'pinia'
import axios from '@/configs/axios'

export const useUserStore = defineStore('userStore', {
    state: () => ({
        list: []
    }),
    actions: {
        async update(payload, uuid) {
            return await axios.put(`v1/user/${uuid}`, payload)
        },
        async me() {
            return await axios.get('v1/user/me')
        }
    }
})