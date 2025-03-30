<template>
    <v-row>
        <v-col cols="12" sm="6" md="3">
            <v-sheet color rounded="xl">
                <v-card elevation="0" color="grey-lighten-5" rounded="xl">
                    <v-card-text>
                        <div class="d-flex flex-column align-center ga-3">
                            <v-avatar color="orange-darken-3" variant="tonal" size="x-large">
                                <v-icon icon="fas fa-user" color="orange-darken-3"></v-icon>
                            </v-avatar>

                            <h3 class="text-h5 font-weight-bold text-orange-darken-3">
                                <vue3autocounter :startAmount="0" :endAmount="200" :duration="2" :autoinit="true" separator="."/>
                            </h3>

                            <span class="font-weight-bold text-orange-darken-3">Participantes</span>
                        </div>
                    </v-card-text>
                </v-card>
            </v-sheet>
        </v-col>

        <v-col cols="12" sm="6" md="3">
            <v-sheet color rounded="xl">
                <v-card elevation="0" color="grey-lighten-5" rounded="xl">
                    <v-card-text>
                        <div class="d-flex flex-column align-center ga-3">
                            <v-avatar color="blue-darken-3" variant="tonal" size="x-large">
                                <v-icon icon="fas fa-calendar-days" color="blue-darken-3"></v-icon>
                            </v-avatar>

                            <h3 class="text-h5 font-weight-bold text-blue-darken-3">
                                <vue3autocounter :startAmount="0" :endAmount="180" :duration="2" :autoinit="true" separator="."/>
                            </h3>

                            <span class="font-weight-bold text-blue-darken-3">Web aulas</span>
                        </div>
                    </v-card-text>
                </v-card>
            </v-sheet>
        </v-col>

        <v-col cols="12" sm="6" md="3">
            <v-sheet color rounded="xl">
                <v-card elevation="0" color="grey-lighten-5" rounded="xl">
                    <v-card-text>
                        <div class="d-flex flex-column align-center ga-3">
                            <v-avatar color="green-darken-3" variant="tonal" size="x-large">
                                <v-icon icon="fas fa-users" color="text-green-darken-3"></v-icon>
                            </v-avatar>

                            <h3 class="text-h5 font-weight-bold text-green-darken-3">
                                <vue3autocounter :startAmount="0" :endAmount="900" :duration="2" :autoinit="true" separator="."/>
                            </h3>

                            <span class="font-weight-bold text-green-darken-3">Participantes por web aula</span>
                        </div>
                    </v-card-text>
                </v-card>
            </v-sheet>
        </v-col>

        <v-col cols="12" sm="6" md="3">
            <v-sheet color rounded="xl">
                <v-card elevation="0" color="grey-lighten-5" rounded="xl">
                    <v-card-text>
                        <div class="d-flex flex-column align-center ga-3">
                            <v-avatar color="grey-darken-3" variant="tonal" size="x-large">
                                <v-icon icon="fas fa-map-location-dot" color="grey-darken-3"></v-icon>
                            </v-avatar>

                            <h3 class="text-h5 font-weight-bold text-grey-darken-3">
                                <vue3autocounter :startAmount="0" :endAmount="300" :duration="2" :autoinit="true" separator="."/>
                            </h3>

                            <span class="font-weight-bold text-grey-darken-3">Municípios participantes</span>
                        </div>
                    </v-card-text>
                </v-card>
            </v-sheet>
        </v-col>
    </v-row>
</template>

<script setup>
import { onMounted } from 'vue'
import Vue3autocounter from 'vue3-autocounter';

import { useAppStore } from '@/stores/appStore'

const appStore = useAppStore()

onMounted(() => {
    appStore.pageTitle = 'Dashboard'
})
</script>

<style>

</style>