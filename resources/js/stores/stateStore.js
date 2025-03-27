import { defineStore } from 'pinia'
import axios from '@/configs/axios'

export const useStateStore = defineStore('stateStore', {
    state: () => ({
        list: []
    }),
    actions: {
        async index(payload) {
            const query = new URLSearchParams(payload)

            return await axios.get(`v1/state?${query}`)
        }
    }
})