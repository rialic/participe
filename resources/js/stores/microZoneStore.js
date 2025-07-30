import { defineStore } from 'pinia'
import axios from '@/configs/axios'

export const useMicroZoneStore = defineStore('microZoneStore', {
    state: () => ({
        list: []
    }),
    actions: {
        async index(payload) {
            const query = new URLSearchParams(payload)

            return await axios.get(`v1/micro-zone?${query}`)
        }
    }
})