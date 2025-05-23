<template>
    <v-container>
    <v-row>
        <v-col
            v-for="card in cards" :key="card.id"
            cols="12"
            sm="6"
            md="3"
        >
        <v-card
            @click.prevent="router.push({ name: card.routerName })"
            border="warning sm opacity-100"
            class="card-dashboard d-flex align-center justify-center mx-auto h-120x cursor-pointer"
            elevation="0"
        >
            <v-card-text class="d-flex flex-column ga-4 align-center">
                <h3 class="text-h6 text-orange-darken-3" style="font-weight: 500;">
                    {{ card.title }}
                </h3>

                <v-icon :icon="`${card.icon}`" class="text-orange-darken-3"></v-icon>
            </v-card-text>
        </v-card>
        </v-col>
    </v-row>
  </v-container>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useAppStore } from '@/stores/appStore'
import { useRouter } from 'vue-router'

const appStore = useAppStore()

const router = useRouter()
const cards = ref([
  {
    id: 1,
    title: 'Estabelecimentos',
    routerName: 'smart.establishment',
    icon: 'far fa-hospital'
  },
  {
    id: 2,
    title: 'Profissionais',
    routerName: 'smart.professionals',
    icon: 'fas fa-user-nurse'
  },
  {
    id: 3,
    title: 'Tele-educação',
    routerName: 'smart.webs',
    icon: 'fas fa-house-laptop'
  },
  {
    id: 4,
    title: 'Teleconsultoria',
    routerName: 'smart.teleconsulting',
    icon: 'fas fa-users'
  }
])

/* onMounted */
onMounted(async () => {
    appStore.pageTitle = 'Smart'
})
</script>

<style>
.card-dashboard {
  transition: transform 0.3s;
}

.card-dashboard:hover {
  transform: translateY(-5px);
}
</style>