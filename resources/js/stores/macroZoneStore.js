import { defineStore } from 'pinia'
import axios from '@/configs/axios'

export const useMacroZoneStore = defineStore('macroZoneStore', {
    state: () => ({
        list: []
    }),
    actions: {
        async index(payload) {
            const query = new URLSearchParams(payload)

            return await axios.get(`v1/macro-zone?${query}`)
        }
    }
})