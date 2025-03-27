<template>
    Dashboard Here
</template>

<script setup>
import { onMounted, ref } from 'vue'

import { useAppStore } from '@/stores/appStore'

const appStore = useAppStore()

onMounted(() => {
    appStore.pageTitle = 'Dashboard'
})
</script>

<style>

</style>