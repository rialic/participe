import { defineStore } from 'pinia'
import axios from '@/configs/axios'

export const useEventStore = defineStore('eventStore', {
    state: () => ({
        title: null,
        list: []
    }),
    actions: {
        async show(uuid) {
            return await axios.get(`v1/events/${uuid}`)
        },
        async index(payload) {
            const query = new URLSearchParams(payload)

            return await axios.get(`v1/events?${query}`)
        },
        async store(payload) {
            return await axios.post('v1/events', payload, { headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
        },
        async update(payload, uuid) {
            return await axios.post(`v1/events/${uuid}`, payload, { headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
        },
        async delete(uuid) {
            return await axios.delete(`v1/events/${uuid}`)
        },
        async syncParticipants(payload) {
            return await axios.post('v1/events/sync-participants', payload)
        },
        async storeParticipantRating(uuid, payload) {
            return await axios.put(`v1/events/participant-rating/${uuid}`, payload)
        }
    }
})