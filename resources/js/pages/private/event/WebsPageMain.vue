<template>
    <h4 class="text-grey-darken-3">{{ eventStore.title }}</h4>

    <router-view/>
</template>

<script setup>
import { onMounted } from 'vue'
import { useEventStore } from '@/stores/eventStore'

const eventStore = useEventStore()

</script>
