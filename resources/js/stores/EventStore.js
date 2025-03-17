import { defineStore } from 'pinia'
import axios from '@/configs/axios'

export const useEventStore = defineStore('eventStore', {
    state: () => ({
        list: []
    }),
    actions: {
        async index(payload) {
            const query = new URLSearchParams(payload)

            return await axios.get(`v1/event?${query}`)
        },
        async syncParticipants(payload) {
            return await axios.post('v1/event/sync-participants', payload)
        },
        async storeParticipantRating(uuid, payload) {
            return await axios.put(`v1/event/participant-rating/${uuid}`, payload)
        }
    }
})