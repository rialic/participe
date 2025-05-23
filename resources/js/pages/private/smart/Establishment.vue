<template>
    <h4 class="text-grey-darken-3">{{ smartStore.title }}</h4>

    <v-alert
      v-model="alert"
      closable
      title="Erro ao enviar os dados"
      :text="errorMessage"
      type="error"
      class="mb-4">
    </v-alert>

    <div class="d-flex justify-end ga-2 mb-2">
        <v-btn-group variant="flat" density="small" rounded="sm" color="teal-lighten-1">
            <v-btn
                variant="flat"
                class="font-weight-bold text-none h-100"
                size="small"
                @click="send('NA')"
                :append-icon="icon('far fa-paper-plane')"
                min-height="26"
            >
                Enviar
            </v-btn>

            <v-menu v-model="menu" location="bottom">
                <template v-slot:activator="{ props }">
                    <v-btn
                        v-bind="props"
                        variant="flat"
                        class="font-weight-bold text-none"
                        size="small"
                        min-width="26"
                    >
                        <v-icon icon="fa-chevron-down" size="small"></v-icon>
                    </v-btn>
                </template>

                <v-list>
                    <v-list-item
                        v-for="(item, index) in items" :key="index" :value="item"
                        @click="send(item.substring(item.indexOf(' ') + 1, item.length))"
                    >
                        <v-list-item-title class="fs-12x">{{ item }}</v-list-item-title>
                    </v-list-item>
                </v-list>
            </v-menu>
        </v-btn-group>
    </div>

    <div class="d-flex flex-column align-end ga-1 mb-8">
        <span class="fs-14x">NA - Novo/Atualização (Incrementa ou atualiza os dados existentes)</span>
        <span class="fs-14x">RE - Reprocessamento (Apaga TODOS os dados da competência e insere os novos)</span>
    </div>

    <v-card flat>
        <v-card-title v-if="selectedTab === 'default'" class="d-flex justify-space-between align-center px-0 mb-4">
            <v-spacer class="d-none d-md-block"></v-spacer>

            <v-text-field
                v-model="search"
                density="compact"
                label="Pesquisar"
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

        <v-tabs
          v-model="selectedTab"
          color="orange-darken-3"
        >
          <v-tab
            @click.prevent.stop="setDynamicTabStyle('default')"
            tag="div"
            :variant="`${selectedTab === 'default' ? 'tonal' : 'plain'}`"
            hide-slider slim
            :border="`${selectedTab === 'default' ? 'warning opacity-100 md b-0' : 'b-md'}`"
            value="default"
            :style="tabStyle"
            class="text-none font-weight-bold">
                Envio padrão
          </v-tab>

          <v-tab
            @click.prevent.stop="setDynamicTabStyle('custom')"
            tag="div"
            :variant="`${selectedTab === 'custom' ? 'tonal' : 'plain'}`"
            hide-slider slim
            :border="`${selectedTab === 'custom' ? 'warning opacity-100 md b-0' : 'b-md'}`"
            value="custom"
            :style="tabStyle"
            class="text-none font-weight-bold">
                Envio personalizado
            </v-tab>

            <v-tab
                tag="div"
                variant="plain"
                hide-slider
                slim
                border="b-md"
                :readonly="true"
                class="w-100">
                    &nbsp;
                </v-tab>
        </v-tabs>

        <div class="my-2"></div>

        <v-tabs-window v-model="selectedTab">
            <v-tabs-window-item value="default">
                    <div class="mt-1">
                        <v-sheet border="thin" rounded>
                            <v-data-table-server
                                v-model:search="search"
                                item-value="uuid"
                                :headers="smartEstablishmentsHeader"
                                :items="smartEstablishmentList"
                                :items-per-page="smartEstablishmentItemsPerPage"
                                :items-length="smartEstablishmentTotal"
                                :loading="loading"
                                :page-text="`${smartEstablishmentFrom || 0}-${smartEstablishmentTo || 0} de ${smartEstablishmentTotal}`"
                                :hover="true"
                                :first-icon="icon('fas fa-step-backward', 'fs-16x')"
                                :prev-icon="icon('fas fa-chevron-left', 'fs-16x')"
                                :next-icon="icon('fas fa-chevron-right', 'fs-16x')"
                                :last-icon="icon('fas fa-step-forward', 'fs-16x')"
                                items-per-page-text="Itens por página"
                                density="comfortable"
                                @update:page="navigate"
                                @update:options="searchSmartEstablishments">
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
                                            {{ item.name }}
                                        </td>

                                        <td>
                                            {{ item.cnes }}
                                        </td>

                                        <td>
                                            {{ (item.tdiagn) ? 'Sim' : 'Não' }}
                                        </td>

                                        <td>
                                            {{ (item.teduca) ? 'Sim' : 'Não' }}
                                        </td>

                                        <td>
                                            {{ (item.tconsul) ? 'Sim' : 'Não' }}
                                        </td>
                                    </tr>
                                </template>
                            </v-data-table-server>
                        </v-sheet>
                    </div>
            </v-tabs-window-item>

            <v-tabs-window-item value="custom">
                <div class="mt-1">
                    <v-row>
                        <v-col col="12" md="4" class="pt-4 pb-1">
                            <v-text-field
                                label="Data *"
                                v-model="form.custom_date"
                                density="small"
                                required variant="outlined"
                                color="orange-darken-4"
                                hint="Informe o perído que deseja enviar"
                                :persistent-hint="true"
                                placeholder="Data"
                                autocomplete="false"
                                v-maska:[cpf]="'##/####'"
                                clearable>
                            </v-text-field>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col col="12" md="6">
                            <tags-input
                              v-model="form.include_tag_list"
                              :validate-tag="validateTag"
                              :hint="'Lista do CNES que serão enviados, CNES da lista principal serão excluídos'"
                              placeholder="Incluir CNES"
                            />
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col col="12" md="6">
                            <tags-input
                              v-model="form.exclude_tag_list"
                              :validate-tag="validateTag"
                              placeholder="Excluir CNES"
                              :hint="'Lista do CNES que serão excluídos da lista principal'"
                            />
                        </v-col>
                    </v-row>
                </div>
            </v-tabs-window-item>
        </v-tabs-window>

  </v-card>
</template>

<script setup>
import TagsInput from '@/components/TagsInput.vue'
import useIcon from '@/composables/useIcon'
import { presetFilter, presetForm } from '@/helpers'
import { onMounted, ref, watch } from 'vue'

import { useAppStore } from '@/stores/appStore'
import { useSmartStore } from '@/stores/smartStore'

const appStore = useAppStore()
const smartStore = useSmartStore()

const { icon } = useIcon()
const search = ref()
const isTypingInSearch = ref(false)
const timeoutId = ref()
const menu = ref(false)
const items = ref(['Enviar NA', 'Enviar RE'])
const selectedTab = ref()
const tabStyle = ref({})
const currentPage = ref()
const sortBy = ref('cnes')
const direction = ref('asc')
const filter = ref({
    scope_search_establishment: null,
})
const form = ref({
    custom_date: null,
    include_tag_list: [],
    exclude_tag_list: [],
})
const smartEstablishmentsHeader = ref([
    { title: 'Nane', icon: 'fa-sort' },
    { title: 'CNES' },
    { title: 'Telediagnóstico' },
    { title: 'Tele-educação' },
    { title: 'Teleconsultoria' },
])
const loading = ref(false)
const navigating = ref(false)
const smartEstablishmentList = ref([])
const smartEstablishmentTotal = ref(0)
const smartEstablishmentItemsPerPage = ref(15)
const smartEstablishmentFrom = ref()
const smartEstablishmentTo = ref()
const alert = ref(false)
const errorMessage = ref()

/* onMounted */
onMounted(async () => {
    appStore.pageTitle = 'Estabelecimentos'
    smartStore.title = 'Lista Estabelecimentos'

    setDynamicTabStyle('default')
})

/* watch */
watch(() => search.value, () => {
    isTypingInSearch.value = true
})

watch(() => selectedTab.value, (selectedTab) => {
    if (selectedTab === 'default') {
        Object.entries(form.value).forEach(([key, _]) => {
            form.value[key] = (key === 'custom_date') ? null : []
        })
    }
})

/* functions */
function validateTag(tag) {
    const regex = /[0-9]{7}/g

    return regex.test(tag) || 'Tag deve ter 7 caracteres numéricos'
}

function setDynamicTabStyle(tabValue) {
    tabStyle.value = selectedTab.value === tabValue ? { 'border-top-left-radius': '6px', 'border-top-right-radius': '6px' } : {}
}

function navigate(page) {
    if(isTypingInSearch.value && page === 1) {
        navigating.value = false
        isTypingInSearch.value = false

        return
    }

    isTypingInSearch.value = false
    navigating.value = true
}

function searchSmartEstablishments({ page, itemsPerPage }) {
    clearTimeout(timeoutId.value)

    if(navigating.value) {
        loadSmartEstablishments(page, itemsPerPage)
        navigating.value = false

        return
    }

    if (!search.value) {
        loadSmartEstablishments(page, itemsPerPage)
    }

    if (search.value && search.value.length >= 3) {
        timeoutId.value = setTimeout(() => loadSmartEstablishments(page, itemsPerPage), 1200)
    }
}

function sortItems(column) {
    const sortByList = { 'Nome': 'name' }
    const header = smartEstablishmentsHeader.value.find((header) => header.title === column)
    header.icon = (header.icon === 'fa-arrow-down-wide-short' || header.icon === 'fa-sort') ? 'fa-arrow-up-wide-short' : 'fa-arrow-down-wide-short'

    smartEstablishmentsHeader.value.forEach((header) => {
        if (header.title !== column && header.icon) {
            header.icon = 'fa-sort'
        }
    })

    sortBy.value = sortByList[column]
    direction.value = (header.icon === 'fa-arrow-up-wide-short') ? 'asc' : 'desc'
    loadSmartEstablishments(currentPage.value, smartEstablishmentItemsPerPage.value)
}

async function loadSmartEstablishments(page, itemsPerPage) {
    loading.value = true
    currentPage.value = page
    filter.value.scope_search_establishment = search.value
    const payload = { page: currentPage.value, limit: itemsPerPage, order_by: sortBy.value, direction: direction.value, ...presetFilter(filter.value, true) }
    const response = await smartStore.indexEstablishments(payload)
    loading.value = false

    if (response.ok) {
        smartStore.list = response.data || []
        smartEstablishmentList.value = smartStore.list
        smartEstablishmentTotal.value = response.meta?.total
        smartEstablishmentFrom.value = response.meta?.from
        smartEstablishmentTo.value = response.meta?.to
        smartEstablishmentItemsPerPage.value = response.meta?.per_page
    }
}

async function send(type) {
    loading.value = true
    const payload = preparePayloadFromForm(type)
    const response = await smartStore.sendEstablishments(payload)
    loading.value = false

    if (response.status !== 200) {
        alert.value = true
        errorMessage.value = response.data.errors.message
    }
}

function preparePayloadFromForm(type) {
    return { dispatch_type: type, ...presetForm(form.value) }
}
</script>