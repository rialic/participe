<template>
<v-container class="h-100">
        <v-row align="center" justify="center" class="h-100">
            <v-col cols="12" sm="10">
                <v-sheet elevation="1" border="thin" rounded>
                    <v-card variant="flat" class="pa-4">
                        <v-row>
                            <v-col col="12">
                                <div class="mb-8">
                                    <h3 class="text-grey-darken-3">Certificados</h3>

                                    <span class="fs-14x font-weight-bold text-grey-darken-2">
                                      Informe seu cpf no campo abaixo para gerar o certificado de participação
                                    </span>
                                </div>

                                <div class="d-flex mb-4 mt-6 w-100 w-md-60 w-lg-40 w-xl-30">
                                    <v-text-field
                                        label="CPF *"
                                        v-model="cpf"
                                        :rules="[rules.cpf]"
                                        :error-messages="errorMessage('cpf')"
                                        density="small"
                                        required variant="outlined"
                                        color="orange-darken-4"
                                        placeholder="CPF"
                                        autocomplete="false"
                                        v-maska:[cpf]="'###.###.###-##'"
                                        clearable>

                                        <template #append>
                                            <v-btn
                                            :disabled="!enableSearchParticipant"
                                            class="font-weight-bold text-none fs-13x"
                                            variant="flat"
                                            rounded="sm"
                                            color="teal-lighten-1"
                                            :prepend-icon="icon('fas fa-magnifying-glass')"
                                            size="small"
                                            @click="onSearchParticipant">
                                            Pesquisar
                                            </v-btn>
                                        </template>
                                    </v-text-field>
                                </div>

                                <v-card v-if="certificateStore.participant?.events?.length" elevation="0" border="thin" rounded="md">
                                    <v-data-table
                                        :headers="eventsDtHeader"
                                        :items="certificateStore.participant.events"
                                        item-value="theme"
                                        density="comfortable"
                                        v-model="selectedEvent"
                                        hide-default-footer
                                        :hide-default-header="mobile"
                                        :class="{ 'mobile': mobile}"
                                        hover="true">
                                        <template #top>
                                            <v-toolbar density="small" class="px-2" color="grey-lighten-4">
                                                <h4>Certificado - Webaulas</h4>
                                            </v-toolbar>
                                        </template>

                                        <template #headers="{ columns }">
                                            <tr>
                                                <template v-for="column in columns" :key="column.key">
                                                    <th :class="{ 'text-right': column.title === 'Imprimir' }">
                                                        <span class="font-weight-bold">{{ column.title }}</span>
                                                    </th>
                                                </template>
                                            </tr>
                                        </template>

                                        <template v-if="mobile" #item="{ item }">
                                          <tr class="bg-orange-lighten-5">
                                            <td>
                                                <div class="d-flex pa-0 flex-wrap ga-6">
                                                  <div data-label="Tema" class="font-weight-bold">{{ item.name }}</div>

                                                  <div data-label="Início">{{ maskDate(item.start_at) }}</div>

                                                  <div data-label="Fim">{{ maskDate(item.end_at) }}</div>

                                                  <div data-label="Imprimir">
                                                    <v-btn
                                                        variant="plain"
                                                        size="small"
                                                        density="compact"
                                                        icon="fas fa-file-arrow-down"
                                                        class="px-0"
                                                        @click="onPrintCertificate(item)">
                                                    </v-btn>
                                                  </div>
                                                </div>
                                            </td>
                                          </tr>
                                        </template>

                                        <template v-if="!mobile" #item="{ item }">
                                            <tr>
                                                <td>
                                                    <span class="font-weight-bold">{{ item.name }}</span>
                                                </td>

                                                <td>
                                                    <span>{{ maskDate(item.start_at) }}</span>
                                                </td>

                                                <td>
                                                    <span>{{ maskDate(item.end_at) }}</span>
                                                </td>

                                                <td class="d-flex justify-end align-center">
                                                    <v-btn
                                                        variant="plain"
                                                        size="small"
                                                        density="compact"
                                                        icon="fas fa-file-arrow-down"
                                                        class="px-0"
                                                        @click="onPrintCertificate(item)">
                                                    </v-btn>
                                                </td>
                                            </tr>
                                        </template>
                                    </v-data-table>
                                </v-card>

                                <no-content-found v-if="showNotContentFound"></no-content-found>
                            </v-col>
                        </v-row>
                    </v-card>
                </v-sheet>
            </v-col>
        </v-row>

        <rating-modal
            :event="selectedEvent"
            @print="printCertificate"/>
</v-container>
</template>

<script setup>
import { ref, onMounted, h } from 'vue'
import { maskDate, cpfValidated, errorMessage } from '@/helpers'
import { useDisplay } from 'vuetify'
import useIcon from '@/composables/useIcon'

import RatingModal from '@/pages/guest/components/RatingModal.vue'

import { useCertificateStore } from '@/stores/certificateStore'
import { useEventStore } from '@/stores/eventStore'
import { useAlertStore } from '@/stores/alertStore'

const alertStore = useAlertStore()
const certificateStore = useCertificateStore()
const eventStore = useEventStore()
const { icon } = useIcon()

const { mobile } = useDisplay()
const cpf = ref()
const errors = ref([])
const showNotContentFound = ref(false)
const selectedEvent = ref()
const enableSearchParticipant = ref(false)
const eventsDtHeader = ref([{ title: 'Tema', key: 'webclass' }, { title: 'Início', key: 'start_at' }, { title: 'Fim', key: 'end_at' }, { title: 'Imprimir', key: 'print'}])

/* onMounted */
onMounted(() => {
    certificateStore.participant = null
    showNotContentFound.value = false
})

/* rules */
const rules = ref({
  cpf: (value) => {
    if (value?.length === 14) {
        if (cpfValidated(value)) {
            enableSearchParticipant.value = true

            return true
        }

        return 'CPF Inválido.'
    }

        if (certificateStore.participant?.events.length) {
            certificateStore.participant.events = []
        }

        showNotContentFound.value = false
        enableSearchParticipant.value = false
        errors.value = []
  }
})

/* functions */
async function onSearchParticipant() {
    const response = await certificateStore.show(cpf.value)
    showNotContentFound.value = false

    if(response.ok) {
        const data = response.data
        certificateStore.participant = Array.isArray(data) ? null : data

        if(!certificateStore.participant) {
            showNotContentFound.value = true
        }

        return
    }

    if (response.status === 422) {
        errors.value = response.data.errors
    }
}

async function onPrintCertificate(item) {
    selectedEvent.value = item
    certificateStore.showRatingModal = !selectedEvent.value.rated_at

    if (!certificateStore.showRatingModal) {
        downloadFile()
    }
}

async function printCertificate(form) {
    certificateStore.showRatingModal = false
    const response = await eventStore.storeParticipantRating(selectedEvent.value.uuid, { participant: certificateStore.participant.uuid, ...form })

    if(response.ok) {
        alertStore.showAlert = false
        selectedEvent.value.rated_at = new Date()
        downloadFile()
    }
}

async function downloadFile() {
    const response = await certificateStore.print({ participant: certificateStore.participant, event: selectedEvent.value })
    const link = document.createElement('a');
    const fileURL = URL.createObjectURL(response.data)
    link.download = `Certificado ${selectedEvent.value.name} - ${certificateStore.participant.name}`
    link.href = fileURL

    document.body.appendChild(link)
    link.click()
    document.body.removeChild(link)
    URL.revokeObjectURL(fileURL)
}
</script>