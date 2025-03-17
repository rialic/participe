import { defineStore } from 'pinia'
import axios from '@/configs/axios'

export const useCityStore = defineStore('cityStore', {
    state: () => ({
        list: []
    }),
    actions: {
        index(payload) {
            const query = new URLSearchParams(payload)

            return axios.get(`v1/city?${query}`)
        }
    }
})