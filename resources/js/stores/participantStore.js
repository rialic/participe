import { defineStore } from 'pinia'
import axios from '@/configs/axios'

export const useParticipantStore = defineStore('participantStore', {
    state: () => ({}),
    actions: {
        async show(cpf) {
            return await axios.get(`v1/participant?cpf=${cpf}`)
        },
        async store(payload){
            return await axios.post('v1/participant', payload)
        },
        async update(payload){
            return await axios.put(`v1/participant/${payload.uuid}`, payload)
        }
    }
})