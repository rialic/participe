<template>
    <div class="d-flex justify-end ga-2 mb-8">
        <v-btn
            :prepend-icon="icon('fas fa-plus')"
            variant="flat"
            color="teal-lighten-1"
            rounded="sm"
            class="font-weight-bold text-none"
            size="small"
            @click="router.push({ name: 'webs.new' })">
            Cadastrar
        </v-btn>

        <v-btn
            v-can:[user.abilities]="'EVENT.WEBCLASS-REPORT'"
            :prepend-icon="icon('far fa-file-lines')"
            variant="flat"
            color="grey-lighten-4"
            rounded="sm"
            border
            class="font-weight-bold text-none"
            size="small"
            @click="router.push({ name: 'webs.report' })">
            Relatórios
        </v-btn>
    </div>

    <v-card flat>
        <v-card-title class="d-flex justify-space-between align-center px-0 mb-4">
            <v-spacer class="d-none d-md-block"></v-spacer>

            <v-text-field
                v-model="search"
                density="compact"
                variant="solo-filled"
                flat
                hide-details
                clearable
                single-line>
                <template #prepend-inner>
                    <v-icon icon="fas fa-magnifying-glass" size="x-small"></v-icon>
                </template>
            </v-text-field>
        </v-card-title>

    <v-sheet border="thin" rounded>
        <v-data-table-server
            v-model:search="search"
            item-value="uuid"
            :headers="eventsHeader"
            :items="eventList"
            :items-per-page="eventItemsPerPage"
            :items-length="eventTotal"
            :loading="loading"
            :page-text="`${eventFrom || 0}-${eventTo || 0} de ${eventTotal}`"
            :hover="true"
            :first-icon="icon('fas fa-step-backward', 'fs-16x')"
            :prev-icon="icon('fas fa-chevron-left', 'fs-16x')"
            :next-icon="icon('fas fa-chevron-right', 'fs-16x')"
            :last-icon="icon('fas fa-step-forward', 'fs-16x')"
            items-per-page-text="Itens por página"
            density="comfortable"
            @update:page="navigate"
            @update:options="searchEvents">
            <template #headers="{ columns }">
                <tr>
                    <template v-for="column in columns" :key="column.key">
                        <th>
                            <div :class="['d-flex align-center ga-1', { 'justify-end':  column.title === 'Ações'}]">
                                <span class="font-weight-bold">{{ column.title }}</span>

                                <template v-if="column.icon">
                                    <v-icon
                                        :icon="`fas ${column.icon}`"
                                        size="x-small"
                                        class="cursor-pointer"
                                        @click.prevent.stop="sortItems(column.title)">
                                    </v-icon>
                                </template>
                            </div>
                        </th>
                    </template>
                </tr>
            </template>

            <template #item="{ item }">
                <tr>
                    <td>
                        {{ item.theme }}
                    </td>

                    <td>
                        {{ item.start }}
                    </td>

                    <td>
                        {{ item.end }}
                    </td>

                    <td>
                        {{ item.organization }}
                    </td>

                    <td>
                        {{ item.desc_bireme }}
                    </td>

                    <td class="d-flex justify-end align-center ga-3">
                        <v-btn
                            type="button"
                            @click="router.push({ name: 'webs.edit', params: { uuid: item.uuid } })"
                            variant="plain"
                            size="x-small"
                            density="compact"
                            icon="fas fa-pen"
                            class="px-0">
                        </v-btn>

                        <v-btn
                            type="button"
                            @click="onRemoveWebclass(item)"
                            variant="plain"
                            size="x-small"
                            density="compact"
                            icon="fas fa-trash"
                            class="px-0">
                        </v-btn>
                    </td>
                </tr>
            </template>
        </v-data-table-server>
    </v-sheet>
  </v-card>

  <v-delete-modal
    v-model="showDestroyModal"
    title="Exclusão de webaula"
    message="Tem certeza que deseja excluir a webaula?"
    :extraMessage="webclassToDeleted?.type_notification !== 'none' ? 'O sistema irá notificar os participantes por email da exclusão dessa webaula.' : ''"
    :targetName="webclassToDeleted?.theme"
    @onConfirm="removeWebclass"
    />
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { maskDate, presetFilter } from '@/helpers'
import { useRouter } from 'vue-router'
import useAuth from '@/composables/useAuth'
import useIcon from '@/composables/useIcon'

import { useAppStore } from '@/stores/appStore'
import { useEventStore } from '@/stores/eventStore'

const appStore = useAppStore()
const eventStore = useEventStore()

const router = useRouter()
const { user } = useAuth()
const { icon } = useIcon()
const showDestroyModal = ref(false)
const webclassToDeleted = ref()
const search = ref()
const isTypingInSearch = ref(false)
const timeoutId = ref()
const currentPage = ref()
const sortBy = ref('start_at')
const direction = ref('desc')
const filter = ref({
    scope_search: null,
})
const eventsHeader = ref([
    { title: 'Tema', icon: 'fa-sort' },
    { title: 'Início', icon: 'fa-sort' },
    { title: 'Fim', icon: 'fa-sort' },
    { title: 'Organização' },
    { title: 'Desc. Bireme' },
    { title: 'Ações' }
])
const loading = ref(false)
const navigating = ref(false)
const eventList = ref([])
const eventTotal = ref(0)
const eventItemsPerPage = ref(15)
const eventFrom = ref()
const eventTo = ref()

/* onMounted */
onMounted(async () => {
    appStore.pageTitle = 'Webaulas'
    eventStore.title = 'Lista de webaulas'
})

/* watch */
watch(() => search.value, () => {
    isTypingInSearch.value = true
})

/* functions */
function navigate(page) {
    if(isTypingInSearch.value && page === 1) {
        navigating.value = false
        isTypingInSearch.value = false

        return
    }

    isTypingInSearch.value = false
    navigating.value = true
}

function searchEvents({ page, itemsPerPage }) {
    clearTimeout(timeoutId.value)

    if(navigating.value) {
        loadEvents(page, itemsPerPage)
        navigating.value = false

        return
    }

    if (!search.value) {
        loadEvents(page, itemsPerPage)
    }

    if (search.value && search.value.length >= 3) {
        timeoutId.value = setTimeout(() => loadEvents(page, itemsPerPage), 1200)
    }
}

function sortItems(column) {
    const sortByList = { 'Tema': 'name', 'Início': 'start_at', 'Fim': 'end_at' }
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
    filter.value.scope_search = search.value
    const payload = { page: currentPage.value, limit: itemsPerPage, order_by: sortBy.value, direction: direction.value, ...presetFilter(filter.value) }
    const response = await eventStore.index(payload)
    loading.value = false

    if (response.ok) {
        const data = response.data
        eventStore.list = data || []
        eventList.value = eventStore.list.map((event) => ({
            uuid: event.uuid,
            theme: event.name,
            start: maskDate(event.start_at),
            end: maskDate(event.end_at),
            organization: event.organization,
            desc_bireme: event.desc_bireme?.reduce((acc, descBireme, index) => acc += (index === 0) ? `${descBireme.bireme_code}` : ` / ${descBireme.bireme_code}`, ''),
            type_notification: event.type_notification
        }))
        eventTotal.value = response.meta?.total
        eventFrom.value = response.meta?.from
        eventTo.value = response.meta?.to
        eventItemsPerPage.value = response.meta?.per_page
    }
}

function onRemoveWebclass(webclass) {
    showDestroyModal.value = true
    webclassToDeleted.value = webclass
}

async function removeWebclass() {
    const response = await eventStore.delete(webclassToDeleted.value.uuid)

    if (response.ok) {
        loadEvents(currentPage.value, eventItemsPerPage.value)
    }
}
</script>