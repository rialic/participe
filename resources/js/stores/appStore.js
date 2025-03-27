import { defineStore } from 'pinia'

export const useAppStore = defineStore('appStore', {
    state: () => ({
        showOverlay: false,
        pageTitle: null
    }),
    actions: {
        setOverlay(value) {
            this.showOverlay = value
        }
    }
})