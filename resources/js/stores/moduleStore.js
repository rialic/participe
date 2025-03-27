import { defineStore } from 'pinia'
import axios from '@/configs/axios'

export const useModuleStore = defineStore('moduleStore', {
    state: () => ({
        list: []
    }),
    actions: {
        async index() {
            return await axios.get('v1/module')
        }
    }
})