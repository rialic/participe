import { defineStore } from 'pinia'
import axios from '@/configs/axios'

export const useEstablishmentStore = defineStore('establishmentStore', {
    actions: () => ({
        list: []
    }),
    actions: {
        show(uuid) {
            return axios.get(`v1/establishment/${uuid}`)
        },
        index(payload) {
            const query = new URLSearchParams(payload)

            return axios.get(`v1/establishment?${query}`)
        }
    }
})