import { defineStore } from 'pinia'
import axios from '@/configs/axios'

export const useDescStore = defineStore('descStore', {
    state: () => ({
        list: []
    }),
    actions: {
        async index(payload) {
            const query = new URLSearchParams(payload)

            return await axios.get(`v1/descs?${query}`)
        }
    }
})