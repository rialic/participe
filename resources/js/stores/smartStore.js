import { defineStore } from 'pinia'
import axios from '@/configs/axios'

export const useSmartStore = defineStore('smartStore', {
    state: () => ({
        title: null,
        list: []
    }),
    actions: {
        async indexEstablishments(payload) {
            const query = new URLSearchParams(payload)

            return await axios.get(`v1/smart/establishments?${query}`)
        },
        async sendEstablishments(payload){
            return await axios.post('v1/smart/establishments', payload)
        },
        async indexProfessionals(payload) {
            const query = new URLSearchParams(payload)

            return await axios.get(`v1/smart/professionals?${query}`)
        },
        async sendProfessionals(payload){
            return await axios.post('v1/smart/professionals', payload)
        },
        async indexWebs(payload) {
            const query = new URLSearchParams(payload)

            return await axios.get(`v1/smart/webs?${query}`)
        },
        async sendWebs(payload){
            return await axios.post('v1/smart/webs', payload)
        }
    }
})