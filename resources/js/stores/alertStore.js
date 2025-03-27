import { defineStore } from 'pinia'

export const useAlertStore = defineStore('alertStore', {
    state: () => ({
        showAlert: false,
        typeAlert: null,
        message: null,
    }),
    getters: {
        getTypeAlert: (state) => state.typeAlert || 'error',
        getTitle: (getters) => ({ 'error': 'Erro', 'success': 'Sucesso', 'info': 'Informação', 'warning': 'Aviso' })[getters.getTypeAlert],
        getMessage: (state) => state.message,
    },
    actions: {
        setTypeAlert(type) {
            this.typeAlert = type
        },
        setMessage(message) {
            this.message = message
        }
    }
})