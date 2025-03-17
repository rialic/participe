import { defineStore } from 'pinia'

export const useAppStore = defineStore('appStore', {
    state: () => ({
        showOverlay: false
    }),
    actions: {
        setOverlay(value) {
            this.showOverlay = value
        }
    }
})