<template>
    <v-app class="theme-bg">
        <v-overlay
            opacity="0.80"
            :model-value="appStore.showOverlay"
            persistent
            class="align-center justify-center">
          <v-progress-circular color="primary" size="120" indeterminate>
            Carregando...
          </v-progress-circular>
        </v-overlay>

        <v-alert
            v-if="alertStore.showAlert"
            position="sticky"
            location="top top"
            density="compact"
            variant="elevated"
            prominent="true"
            :title="alertStore.getTitle"
            class="z-index-3000"
            :text="alertStore.message"
            :type="alertStore.getTypeAlert"
            tile
            closable>
        </v-alert>
        <router-view></router-view>
    </v-app>
</template>

<script setup>
import { watch } from 'vue'
import { useAlertStore } from '@/stores/alertStore'
import { useAppStore } from '@/stores/appStore'

const alertStore = useAlertStore()
const appStore = useAppStore()

/* watch */
watch(() => alertStore.showAlert, (showAlert) => {
    if (showAlert) {
        setTimeout(() => alertStore.showAlert = false, 15000)
    }
})
</script>