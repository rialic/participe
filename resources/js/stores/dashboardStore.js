import { defineStore } from 'pinia'
import axios from '@/configs/axios'

export const useDashboardStore = defineStore('dashboardStore', {
    state: () => ({}),
    actions: {
        async dashboard() {
            return await axios.get('v1/dashboard')
        }
    }
})