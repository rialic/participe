<template>
    <div class="d-flex flex-column align-center justify-center min-h-300x">
        <h4>Bem-vindo ao sistema participe</h4>
    </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useAppStore } from '@/stores/appStore'

const appStore = useAppStore()

onMounted(async () => {
    appStore.pageTitle = 'Home'
})
</script>