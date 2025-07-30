<template>
    <div class="d-flex justify-end ga-2 mb-8">
        <v-btn
            :prepend-icon="icon('far fa-file-pdf')"
            variant="flat"
            color="red-darken-1"
            rounded="sm"
            border
            class="font-weight-bold text-none"
            size="small"
            @click="print('pdf')"
        >
            PDF
        </v-btn>

        <v-btn
            :prepend-icon="icon('far fa-file-excel')"
            variant="flat"
            color="teal-lighten-1"
            rounded="sm"
            class="font-weight-bold text-none"
            size="small"
            @click="print('excel')"
        >
            Excel
        </v-btn>
    </div>

    <v-card
        flat
        class="min-h-550x mt-4"
    >
    <div class="mb-8">
        <span class="fs-14x text-grey-darken-3 font-weight-bold">Filtro de pesquisa</span>

        <v-sheet border="thin" rounded class="px-0">
            <div
                class="px-5 pt-6"
            >
                <v-row>
                    <v-col
                        cols="12"
                        md="4"
                        class="py-0"
                    >
                        <v-text-field
                            v-model="searchForm.name"
                            label="Tema"
                            density="small"
                            required
                            variant="outlined"
                            color="orange-darken-4"
                            placeholder="Tema"
                            autocomplete="false"
                            clearable
                        >
                        </v-text-field>
                    </v-col>

                    <v-col
                        cols="12"
                        md="4"
                        class="py-0"
                    >
                        <div class="position-relative w-100">
                            <label class="dp__label fs-12x position-absolute px-1">
                                Data início
                            </label>

                            <vue-date-picker
                                v-model="searchForm.start_at_begin"
                                model-type="yyyy-MM-dd"
                                format="dd/MM/yyyy"
                                locale="pt-br"
                                placeholder="Data início"
                                select-text="Selecionar"
                                cancel-text="Fechar"
                                hide-offset-dates
                                :enable-time-picker="false"
                            >
                                <template #clear-icon="{ clear }">
                                    <i
                                        class="v-icon notranslate v-theme--theme-light v-icon--size-default v-icon--clickable input-slot-image mr-4"
                                        role="button"
                                        aria-hidden="false"
                                        tabindex="0"
                                        @click="clear"
                                    >
                                        <svg
                                            class="svg-inline--fa fa-circle-xmark"
                                            aria-hidden="true"
                                            focusable="false"
                                            data-prefix="fas"
                                            data-icon="circle-xmark"
                                            role="img"
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 512 512"
                                        >
                                            <path
                                                class=""
                                                fill="currentColor"
                                                d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z"
                                            >
                                            </path>
                                        </svg>
                                    </i>
                                </template>
                            </vue-date-picker>

                            <span
                                class="fs-12x px-4"
                                style="color: #B00020"
                            ></span>
                        </div>
                    </v-col>

                    <v-col
                        cols="12"
                        md="4"
                        class="py-0"
                    >
                        <div class="position-relative w-100">
                            <label class="dp__label fs-12x position-absolute px-1">
                                Até
                            </label>

                            <vue-date-picker
                                v-model="searchForm.start_at_end"
                                model-type="yyyy-MM-dd"
                                format="dd/MM/yyyy"
                                locale="pt-br"
                                placeholder="Até"
                                select-text="Selecionar"
                                cancel-text="Fechar"
                                hide-offset-dates
                                :enable-time-picker="false"
                            >
                                <template #clear-icon="{ clear }">
                                    <i
                                        class="v-icon notranslate v-theme--theme-light v-icon--size-default v-icon--clickable input-slot-image mr-4"
                                        role="button"
                                        aria-hidden="false"
                                        tabindex="0"
                                        @click="clear"
                                    >
                                        <svg
                                            class="svg-inline--fa fa-circle-xmark"
                                            aria-hidden="true"
                                            focusable="false"
                                            data-prefix="fas"
                                            data-icon="circle-xmark"
                                            role="img"
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 512 512"
                                        >
                                            <path
                                                class=""
                                                fill="currentColor"
                                                d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z"
                                            >
                                            </path>
                                        </svg>
                                    </i>
                                </template>
                            </vue-date-picker>

                            <span
                                class="fs-12x px-4"
                                style="color: #B00020"
                            ></span>
                        </div>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col
                        cols="12"
                        md="6"
                        class="py-0"
                    >
                        <v-text-field
                            v-model="searchForm.participant"
                            label="Participante"
                            density="small"
                            required
                            variant="outlined"
                            color="orange-darken-4"
                            placeholder="Participante"
                            autocomplete="false"
                            clearable
                        >
                        </v-text-field>
                    </v-col>

                    <v-col
                        cols="12"
                        md="6"
                        class="py-0"
                    >
                        <v-autocomplete
                            label="Ocupação *"
                            v-model="searchForm.cbo"
                            :items="cboStore.list"
                            hint="Digite o nome da sua profissão ou código CBO"
                            density="small"
                            required
                            variant="outlined"
                            no-data-text="Nenhum item encontrado."
                            persistent-hint="true"
                            color="orange-darken-4"
                            placeholder="Ocupação"
                            autocomplete="false"
                            :item-title="(cbo) => `${cbo.name} - CBO: ${cbo.code}`"
                            item-value="uuid"
                            clearable
                        >
                        </v-autocomplete>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col
                        cols="12"
                        md="6"
                        class="py-0"
                    >
                        <v-select
                            v-model="searchForm.organization"
                            label="Organização"
                            :items="['TSMS', 'Fiocruz']"
                            density="small"
                            required
                            variant="outlined"
                            color="orange-darken-4"
                            placeholder="Organização"
                            autocomplete="false"
                            clearable
                        >
                        </v-select>
                    </v-col>

                    <v-col
                        cols="12"
                        md="6"
                        class="py-0"
                    >
                        <v-text-field
                            label="Desc. Bireme"
                            v-model="searchForm.desc_bireme"
                            density="small"
                            required
                            variant="outlined"
                            color="orange-darken-4"
                            placeholder="Desc. Bireme"
                            autocomplete="false"
                            clearable
                        >
                        </v-text-field>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col
                        cols="12"
                        md="3"
                        class="py-0"
                    >
                        <v-select
                            label="UF"
                            v-model="searchForm.state"
                            :items="stateStore.list"
                            density="small"
                            required
                            variant="outlined"
                            color="orange-darken-4"
                            placeholder="UF"
                            autocomplete="false"
                            item-title="name"
                            item-value="uuid"
                            @update:modelValue="loadCity"
                            clearable
                        >
                        </v-select>
                    </v-col>

                    <v-col
                        cols="12"
                        md="3"
                        class="py-0"
                    >
                        <v-autocomplete
                            label="Cidade"
                            v-model="searchForm.city"
                            :items="cityStore.list"
                            density="small"
                            no-data-text="Nenhum item encontrado."
                            required
                            variant="outlined"
                            color="orange-darken-4"
                            placeholder="Cidade"
                            autocomplete="false"
                            item-title="name"
                            item-value="uuid"
                            clearable
                        >
                        </v-autocomplete>
                    </v-col>

                    <v-col
                        cols="12"
                        md="3"
                        class="py-0"
                    >
                        <v-select
                            label="Macro Região"
                            v-model="searchForm.macro_zone"
                            :items="macroZoneStore.list"
                            density="small"
                            required
                            variant="outlined"
                            color="orange-darken-4"
                            placeholder="Macro Região"
                            autocomplete="false"
                            item-title="name"
                            item-value="uuid"
                            @update:modelValue="loadMicroZone"
                            clearable
                        >
                        </v-select>
                    </v-col>

                    <v-col
                        cols="12"
                        md="3"
                        class="py-0"
                    >
                        <v-select
                            label="Micro Região"
                            v-model="searchForm.micro_zone"
                            :items="microZoneStore.list"
                            density="small"
                            required
                            variant="outlined"
                            color="orange-darken-4"
                            placeholder="Micro Região"
                            autocomplete="false"
                            item-title="name"
                            item-value="uuid"
                            clearable
                        >
                        </v-select>
                    </v-col>
                </v-row>

                <div class="d-flex justify-end mb-2">
                    <v-btn
                        :prepend-icon="icon('fas fa-magnifying-glass')"
                        variant="flat"
                        color="teal-lighten-1"
                        rounded="sm"
                        class="font-weight-bold text-none"
                        size="small"
                        @click="onSearch"
                    >
                        Pesquisar
                    </v-btn>
                </div>
            </div>
        </v-sheet>
    </div>

        <v-sheet
            border="thin"
            rounded
        >
            <v-data-table-server
                :headers="eventsHeader"
                :items="eventList"
                :item-value="'uuid'"
                :items-per-page="eventItemsPerPage"
                :items-length="eventTotal"
                :loading="loading"
                :page="currentPage"
                :page-text="`${eventFrom || 0}-${eventTo || 0} de ${eventTotal}`"
                :hover="true"
                :first-icon="icon('fas fa-step-backward', 'fs-16x')"
                :prev-icon="icon('fas fa-chevron-left', 'fs-16x')"
                :next-icon="icon('fas fa-chevron-right', 'fs-16x')"
                :last-icon="icon('fas fa-step-forward', 'fs-16x')"
                items-per-page-text="Itens por página"
                density="comfortable"
                show-expand
                @update:page="navigate"
                @update:options="searchEvents"
            >
                <template #headers="{ columns }">
                    <tr>
                        <template
                            v-for="column in columns" :key="column.key"
                        >
                            <th>
                                <div :class="['d-flex align-center ga-1', { 'justify-end': column.title === 'Ações' }]">
                                    <span class="font-weight-bold">{{ column.title }}</span>

                                    <template v-if="column.icon">
                                        <v-icon
                                            :icon="`fas ${column.icon}`"
                                            size="x-small"
                                            class="cursor-pointer"
                                            @click.prevent.stop="sortItems(column.title)"
                                        >
                                        </v-icon>
                                    </template>
                                </div>
                            </th>
                        </template>
                    </tr>
                </template>

                <template #item="{ item, internalItem, isExpanded, toggleExpand }">
                    <tr>
                        <td>
                            {{ item.theme }}
                        </td>

                        <td>
                            {{ item.start_at }}
                        </td>

                        <td>
                            {{ item.end_at }}
                        </td>

                        <td>
                            {{ item.organization }}
                        </td>

                        <td>
                            {{ item.desc_bireme }}
                        </td>

                        <td>
                            {{ item.participant }}
                        </td>

                        <td>
                            {{ item.signed_up_at }}
                        </td>

                        <td>
                            {{ item.cbo }}
                        </td>

                        <td>
                            {{ item.state }}
                        </td>

                        <td>
                            {{ item.city }}
                        </td>

                        <td>
                            {{ item.macro_zone }}
                        </td>

                        <td>
                            {{ item.micro_zone }}
                        </td>

                        <td>
                            <v-btn
                                :append-icon="isExpanded(internalItem) ? 'fa-chevron-up' : 'fa-chevron-down'"
                                :text="'Avaliação'"
                                class="text-none"
                                color="medium-emphasis"
                                size="small"
                                variant="text"
                                border
                                slim
                                @click="toggleExpand(internalItem)"
                            >
                            </v-btn>
                        </td>
                    </tr>
                </template>

                <template #expanded-row="{ columns, item }">
                    <tr>
                        <td :colspan="columns.length" class="py-2">
                          <v-sheet rounded="lg" border>
                            <v-table density="compact">
                              <tbody class="bg-surface-light">
                                <tr>
                                  <th>Data Avaliação</th>
                                  <th>Avaliação</th>
                                  <th>Avaliação sobre o horário</th>
                                  <th>Sugestão</th>
                                </tr>
                              </tbody>

                              <tbody>
                                <tr>
                                    <td>
                                        <v-chip class="ma-2 fs-12x" label>
                                            <strong>
                                                {{ item.rated_at || 'Não avaliado' }}
                                            </strong>
                                        </v-chip>
                                    </td>

                                    <td>
                                        <v-chip variant="flat" class="ma-2 fs-12x" label :color="processRatingColor(item.rating_event)">
                                            <strong>
                                                {{ item.rating_event }}
                                            </strong>
                                        </v-chip>
                                    </td>

                                    <td>
                                        <v-chip variant="flat" class="ma-2 fs-12x" label :color="processRatingColor(item.rating_event_schedule)">
                                            <strong>
                                                {{ item.rating_event_schedule }}
                                            </strong>
                                        </v-chip>
                                    </td>

                                    <td>
                                        {{ item.hint }}
                                    </td>
                                </tr>
                              </tbody>
                            </v-table>
                          </v-sheet>
                        </td>
                    </tr>
                </template>
            </v-data-table-server>
        </v-sheet>
    </v-card>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { presetFilter } from '@/helpers'
import { format } from 'date-fns'
import useIcon from '@/composables/useIcon'

import { useAppStore } from '@/stores/appStore'
import { useEventStore } from '@/stores/eventStore'
import { useStateStore } from '@/stores/stateStore'
import { useCityStore } from '@/stores/cityStore'
import { useCboStore } from '@/stores/cboStore'
import { useMacroZoneStore } from '@/stores/macroZoneStore'
import { useMicroZoneStore } from '@/stores/microZoneStore'

const appStore = useAppStore()
const eventStore = useEventStore()
const cboStore = useCboStore()
const stateStore = useStateStore()
const cityStore = useCityStore()
const macroZoneStore = useMacroZoneStore()
const microZoneStore = useMicroZoneStore()

const { icon } = useIcon()
const searchForm = ref({
    name: null,
    start_at_begin: null,
    start_at_end: null,
    participant: null,
    cbo: null,
    organization: null,
    desc_bireme: null,
    state: null,
    city: null,
    macro_zone: null,
    micro_zone: null
})
const currentPage = ref()
const sortBy = ref('start_at')
const direction = ref('desc')
const eventsHeader = ref([
    { title: 'Tema', icon: 'fa-sort' },
    { title: 'Início', icon: 'fa-sort' },
    { title: 'Fim' },
    { title: 'Organização' },
    { title: 'Desc. Bireme' },
    { title: 'Participante', icon: 'fa-sort' },
    { title: 'Data Participação' },
    { title: 'Ocupação' },
    { title: 'UF', icon: 'fa-sort' },
    { title: 'Cidade', icon: 'fa-sort' },
    { title: 'Macro Região' },
    { title: 'Micro Região' }
])
const loading = ref(false)
const navigating = ref(false)
const eventList = ref([])
const eventTotal = ref(0)
const eventItemsPerPage = ref(20)
const eventFrom = ref()
const eventTo = ref()

/* onMounted */
onMounted(async () => {
    appStore.pageTitle = 'Relatórios'
    eventStore.title = 'Relatório de Webs'

    loadState()
    loadMacroZone()
    loadCbo()
})

/* Functions */
function onSearch() {
    searchEvents({ page: 1, itemsPerPage: eventItemsPerPage.value })
}

function navigate(page) {
    currentPage.value = page
    navigating.value = true
}

function searchEvents({ page, itemsPerPage }) {
    if(navigating.value) {
        loadEvents(page, itemsPerPage)
        navigating.value = false

        return
    }

    loadEvents(page, itemsPerPage)
}

function sortItems(column) {
    const sortByList = { 'Tema': 'name', 'Início': 'start_at', 'Participante': 'participant', 'UF': 'state', 'Cidade': 'city' }
    const header = eventsHeader.value.find((header) => header.title === column)
    header.icon = (header.icon === 'fa-arrow-down-wide-short' || header.icon === 'fa-sort') ? 'fa-arrow-up-wide-short' : 'fa-arrow-down-wide-short'

    eventsHeader.value.forEach((header) => {
        if (header.title !== column && header.icon) {
            header.icon = 'fa-sort'
        }
    })

    sortBy.value = sortByList[column]
    direction.value = (header.icon === 'fa-arrow-up-wide-short') ? 'asc' : 'desc'
    loadEvents(currentPage.value, eventItemsPerPage.value)
}

async function loadEvents(page, itemsPerPage) {
    loading.value = true
    currentPage.value = page
    const payload = { page: currentPage.value, limit: itemsPerPage, order_by: sortBy.value, direction: direction.value, ...presetFilter(searchForm.value) }
    const response = await eventStore.eventReport(payload)
    loading.value = false

    if (response.ok) {
        const data = response.data
        eventStore.list = data || []
        eventList.value = eventStore.list.map((event) => ({
            uuid: event.uuid,
            theme: event.name,
            start_at: event.start_at,
            end_at: event.end_at,
            organization: event.organization,
            desc_bireme: event.descs,
            participant: event.participant,
            signed_up_at: event.signed_up_at,
            rated_at: event.rated_at,
            rating_event: processRating(event.rating_event),
            rating_event_schedule: processRating(event.rating_event_schedule),
            hint: event.hint,
            cbo: event.cbo,
            state: event.state,
            city: event.city,
            macro_zone: event.macro_zone,
            micro_zone: event.micro_zone,
        }))
        eventTotal.value = response.meta?.total
        eventFrom.value = response.meta?.from
        eventTo.value = response.meta?.to
        eventItemsPerPage.value = response.meta?.per_page
    }
}

async function loadMacroZone() {
  const reponse = await macroZoneStore.index()

  if (reponse.ok) {
    const data = reponse.data
    macroZoneStore.list = data || []
  }
}

async function loadMicroZone(macroZone, selectedMicroZone = null) {
  if (macroZone) {
    const reponse = await microZoneStore.index({ macro_zone: macroZone })

    if (reponse.ok) {
      const data = reponse.data
      searchForm.value.micro_zone = selectedMicroZone
      microZoneStore.list = data || []
    }

    return
  }

  searchForm.value.micro_zone = null
  microZoneStore.list = []
}

async function loadCbo() {
  const reponse = await cboStore.index()

  if (reponse.ok) {
    const data = reponse.data
    cboStore.list = data || []
  }
}

async function loadState() {
  const reponse = await stateStore.index()

  if (reponse.ok) {
    const data = reponse.data
    stateStore.list = data || []
  }
}

async function loadCity(state, selectedCity = null) {
  if (state) {
    const reponse = await cityStore.index({state: state})

    if (reponse.ok) {
      const data = reponse.data
      searchForm.value.city = selectedCity
      cityStore.list = data || []
    }

    return
  }

  searchForm.value.city = null
  cityStore.list = []
}

async function print(type) {
    const payload = { limit: -1, order_by: sortBy.value, direction: direction.value, ...presetFilter(searchForm.value) }
    const response = await eventStore.print(payload, type)

    if (response.status === 200) {
        downloadFile(response.data)
    }
}

async function downloadFile(data) {
    const link = document.createElement('a')
    const fileURL = URL.createObjectURL(data)
    const date = format(new Date(), 'dd-MM-yyyy')
    link.download = `Relatório Web Aulas - ${date}`
    link.href = fileURL

    document.body.appendChild(link)
    link.click()
    document.body.removeChild(link)
    URL.revokeObjectURL(fileURL)
}

function processRating(rating) {
    return {
        '9': 'Não informado',
        '1': 'Muito insatisfeito',
        '2': 'Insatisfeito',
        '3': 'Indiferente',
        '4': 'Satisfeito',
        '5': 'Muito satisfeito',
    }[String(rating)]
}

function processRatingColor(rating) {
    return {
        'Não informado': '',
        'Muito insatisfeito': 'red-darken-4',
        'Insatisfeito': 'red-lighten-1',
        'Indiferente': 'yellow-lighten-1',
        'Satisfeito': 'teal-lighten-1',
        'Muito satisfeito': 'light-blue-darken-1'
    }[String(rating)]
}
</script>

<style></style>