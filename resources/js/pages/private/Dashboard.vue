<template>
    <div class="d-flex justify-space-between">
        <span class="fs-18x text-grey-darken-3 font-weight-bold">Webaulas</span>

        <span class="fs-18x text-grey-darken-3 font-weight-bold">{{ timestamp }}</span>
    </div>

    <v-divider class="mb-4 mt-1"></v-divider>

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
                                <vue3autocounter :startAmount="0" :endAmount="participantsCount" :duration="2" :autoinit="true" separator="."/>
                            </h3>

                            <div v-if="participantsList?.length" class="d-flex justify-center ga-3 align-center">
                                <template v-for="(property, index) in participantsList" :key="index">
                                    <div class="d-flex ga-2">
                                        <v-sheet height="15" width="15" rounded :color="propertyColor(property.name)"></v-sheet>
                                        {{ property.name }} -
                                        <vue3autocounter class="font-weight-bold" :startAmount="0" :endAmount="property.count" :duration="2" :autoinit="true" separator="."/>
                                    </div>
                                </template>
                            </div>

                            <div v-else class="d-flex justify-center ga-3 align-center">
                                &nbsp;
                            </div>

                            <span class="font-weight-bold text-orange-darken-3">Participações</span>
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
                                <vue3autocounter :startAmount="0" :endAmount="webclassCountDone" :duration="2" :autoinit="true" separator="."/>
                            </h3>

                            <div v-if="webclassDoneList?.length" class="d-flex justify-center ga-3 align-center">
                                <template v-for="(property, index) in webclassDoneList" :key="index">
                                    <div class="d-flex ga-2">
                                        <v-sheet height="15" width="15" rounded :color="propertyColor(property.name)"></v-sheet>
                                        {{ property.name }} -
                                        <vue3autocounter class="font-weight-bold" :startAmount="0" :endAmount="property.count" :duration="2" :autoinit="true" separator="."/>
                                    </div>
                                </template>
                            </div>

                            <div v-else class="d-flex justify-center ga-3 align-center">
                                &nbsp;
                            </div>

                            <span class="font-weight-bold text-blue-darken-3">Webaulas realizadas</span>
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
                                <vue3autocounter :startAmount="0" :endAmount="webclassCountScheduled" :duration="2" :autoinit="true" separator="."/>
                            </h3>

                            <div v-if="webclassScheduledList?.length" class="d-flex justify-center ga-3 align-center">
                                <template v-for="(property, index) in webclassScheduledList" :key="index">
                                    <div class="d-flex ga-2">
                                        <v-sheet height="15" width="15" rounded :color="propertyColor(property.name)"></v-sheet>
                                        {{ property.name }} -
                                        <vue3autocounter class="font-weight-bold" :startAmount="0" :endAmount="property.count" :duration="2" :autoinit="true" separator="."/>
                                    </div>
                                </template>
                            </div>

                            <div v-else class="d-flex justify-center ga-3 align-center">
                                &nbsp;
                            </div>

                            <span class="font-weight-bold text-blue-darken-3">Webaulas agendadas</span>
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
                                {{ participantsAvgCount }}
                            </h3>

                            <div v-if="participantsAvgList?.length" class="d-flex justify-center ga-3 align-center">
                                <template v-for="(property, index) in participantsAvgList" :key="index">
                                    <div class="d-flex ga-2">
                                        <v-sheet height="15" width="15" rounded :color="propertyColor(property.name)"></v-sheet>
                                        {{ property.name }} -
                                        <vue3autocounter class="font-weight-bold" :startAmount="0" :endAmount="property.count" :duration="2" :autoinit="true" separator="."/>
                                    </div>
                                </template>
                            </div>

                            <div v-else class="d-flex justify-center ga-3 align-center">
                                &nbsp;
                            </div>

                            <span class="font-weight-bold text-green-darken-3">Participantes por web aula</span>
                        </div>
                    </v-card-text>
                </v-card>
            </v-sheet>
        </v-col>
    </v-row>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import Vue3autocounter from 'vue3-autocounter';

import { useAppStore } from '@/stores/appStore'
import { useDashboardStore } from '@/stores/dashboardStore'

const appStore = useAppStore()
const dashboardStore = useDashboardStore()

const timestamp = ref()
const participantsList = ref()
const webclassScheduledList = ref()
const webclassDoneList = ref()
const participantsAvgList = ref()
const participantsAvgCount = ref()
const cityList = ref()

const participantsCount = computed(() => {
    return participantsList.value?.reduce((acc, organization) => acc += organization.count, 0) || 0
})

const webclassCountScheduled = computed(() => {
    return webclassScheduledList.value?.reduce((acc, organization) => acc += organization.count, 0) || 0
})

const webclassCountDone = computed(() => {
    return webclassDoneList.value?.reduce((acc, organization) => acc += organization.count, 0) || 0
})

const propertyColor = computed(() => {
    return (property) => ({
        'Fiocruz': 'blue-darken-3',
        'TSMS': 'orange-darken-4',
    })[property]
})

onMounted(async () => {
    appStore.pageTitle = 'Dashboard'
    const response = await dashboardStore.dashboard()

    if (response.ok) {
        const data = response.data

        participantsList.value = data.webclass.participants_count
        webclassScheduledList.value = data.webclass.webclass_count_scheduled
        webclassDoneList.value = data.webclass.webclass_count_done
        participantsAvgList.value = data.webclass.participants_avg
        participantsAvgCount.value = data.webclass.participants_avg_count
        cityList.value = data.webclass.cities_count
        timestamp.value = data.timestamp
    }
})
</script>

<style>

</style>