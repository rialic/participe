import { defineStore } from 'pinia'
import axios from '@/configs/axios'

export const useCboStore = defineStore('cboStore', {
    state: () => ({
        list: []
    }),
    actions: {
        async index(payload) {
            const query = new URLSearchParams(payload)

            return await axios.get(`v1/cbo?${query}`)
        }
    }
})