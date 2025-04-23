import { defineStore } from 'pinia'
import axios from '@/configs/axios'

export const useCertificateStore = defineStore('certificateStore', {
    state: () => ({
        showRatingModal: false,
        list: null
    }),
    actions: {
        async index(payload) {
            const query = new URLSearchParams(payload)

            return await axios.get(`v1/certificate?${query}`)
        },
        async print(params) {
            return await axios({ url: `v1/certificate/print/${params.participant}/${params.event}`, method: 'get', responseType: 'blob'})
        }
    }
})