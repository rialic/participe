import { defineStore } from 'pinia'
import axios from '@/configs/axios'

export const useCertificateStore = defineStore('certificateStore', {
    state: () => ({
        showRatingModal: false,
        participant: null
    }),
    actions: {
        async show(payload) {
            return await axios.get(`v1/certificate?cpf=${payload}`)
        },
        async print(params) {
            return await axios({ url: `v1/certificate/print/${params.participant.uuid}/${params.event.uuid}`, method: 'get', responseType: 'blob'})
        }
    }
})