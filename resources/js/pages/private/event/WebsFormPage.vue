<template>
    <div class="d-flex justify-end ga-2 mb-8">
        <v-btn
            type="button"
            :prepend-icon="icon('fas fa-check')"
            variant="flat"
            color="teal-lighten-1"
            rounded="sm"
            class="font-weight-bold text-none"
            size="small"
            @click="save">
            Salvar
        </v-btn>
    </div>

    <v-form @submit.prevent="save">
        <v-sheet class="pa-0 pa-md-4">
            <v-row>
                <v-col cols="12" md="6" class="py-1">
                    <v-text-field
                        label="Tema *"
                        v-model="form.name"
                        :error-messages="errorMessage('name')"
                        density="small"
                        required variant="outlined"
                        color="orange-darken-4"
                        placeholder="Tema"
                        autocomplete="false"
                        clearable>
                    </v-text-field>
                </v-col>

                <v-col cols="12" md="6" class="py-1">
                    <v-select
                        label="Organização *"
                        v-model="form.organization"
                        :items="['TSMS', 'Fiocruz']"
                        :error-messages="errorMessage('organization')"
                        density="small"
                        required variant="outlined"
                        color="orange-darken-4"
                        placeholder="Organização"
                        autocomplete="false"
                        clearable>
                    </v-select>
                </v-col>
            </v-row>

            <v-row>
                <v-col cols="12" class="py-1">
                    <v-text-field
                        label="Link *"
                        v-model="form.room_link"
                        :error-messages="errorMessage('room_link')"
                        density="small"
                        required variant="outlined"
                        color="orange-darken-4"
                        placeholder="Link"
                        autocomplete="false"
                        clearable>
                    </v-text-field>
                </v-col>
            </v-row>

            <v-row>
                <v-col cols="12" class="py-1">
                    <v-autocomplete
                        label="Descritor Bireme *"
                        v-model="form.descs"
                        :items="descsStore.list"
                        :item-title="(desc) => `&nbsp; ${desc.bireme_code} - ${desc.name} - ${truncateText(desc.description, 100)}`"
                        :item-value="(desc) => desc.uuid"
                        :error-messages="errorMessage('descs')"
                        hint="Digite o código bireme que deseja pesquisar"
                        density="small"
                        required variant="outlined"
                        no-data-text="Nenhum item encontrado."
                        persistent-hint="true"
                        color="orange-darken-4"
                        placeholder="Descritor Bireme"
                        autocomplete="false"
                        multiple
                        no-filter
                        closable-chips
                        clearable
                        clear-on-select
                        @update:search="searchDescs">
                        <template #chip="{ item, props }">
                            <v-chip
                                v-bind="props"
                                density="comfortable"
                                label
                                size="small">
                                {{ item.title.slice(0, item.title.indexOf('-')).trim() }}
                            </v-chip>
                        </template>

                        <template #append-item>
                          <div data="intersect-element" ref="intersect-element"/>
                        </template>
                    </v-autocomplete>
                </v-col>
            </v-row>

            <v-row>
                <v-col cols="12" class="py-1">
                    <div class="position-relative">
                        <v-textarea
                            label="Emails a serem notificados do resumo da webaula"
                            v-model="form.summary_emails"
                            :error-messages="emailsSummaryMessageErrors.join(' ')"
                            rows="3"
                            density="small"
                            variant="outlined"
                            color="orange-darken-4"
                            hint="Emails que receberão o resumo da webaula. E-mails devem ser separados por espaço."
                            persistent-hint
                            placeholder="Emails a serem notificados do resumo da webaula"
                            autocomplete="false"
                            clearable
                            @input="handleEmailsForSummary"
                            @click:clear="presetEmailsForSummary">
                        </v-textarea>

                        <v-btn
                            icon="fas fa-check"
                            class="position-absolute"
                            style="right: -15px; bottom: 15px;"
                            size="x-small"
                            :color="form.summary_emails ? 'teal-lighten-1' : ''"
                            :disabled="!form.summary_emails"
                            @click.prevent.stop="presetEmailsForSummary">
                        </v-btn>
                    </div>

                    <div class="mt-2">
                        <span>E-mails de palestrantes e/ou usuários a serem notificados dos resumos de webaulas</span>

                        <v-sheet v-if="emailSummaryList.length" border="thin" class="d-flex flex-wrap ga-2 pa-2">
                            <template v-for="(email, index) in emailSummaryList" :key="index">
                                <v-chip
                                    label
                                    variant="flat"
                                    :color="emailsSummaryWithErrors.includes(email) ? 'red-darken-1' : ''"
                                    closable>
                                    <template #default>
                                        {{ email }}
                                    </template>

                                    <template #close>
                                        <v-icon icon="fas fa-circle-xmark" @click.prevent.stop="purgeEmailsForSummary(index)"></v-icon>
                                    </template>
                                </v-chip>
                            </template>
                        </v-sheet>

                        <v-sheet v-else border="thin" class="d-flex flex-wrap ga-2 pa-2">
                            Nenhum email foi adicionado na lista
                        </v-sheet>
                    </div>
                </v-col>
            </v-row>

            <v-row>
                <v-col cols="12" md="6">
                    <v-row>
                        <v-col cols="12" md="6" class="pb-1">
                            <div class="position-relative w-100">
                                <label
                                    :class="['dp__label fs-12x position-absolute px-1', { 'dp__label_invalid': Boolean(errorMessage('start_at')) }]">
                                    Data início *
                                </label>

                                <vue-date-picker
                                    v-model="form.start_at"
                                    model-type="yyyy-MM-dd HH:mm:ss"
                                    format="dd/MM/yyyy HH:mm:ss"
                                    locale="pt-br"
                                    placeholder="Data início *"
                                    :state="!Boolean(errorMessage('start_at'))"
                                    :min-date="new Date()"
                                    :max-date="form.end_at"
                                    month-name-format="long"
                                    select-text="Selecionar"
                                    cancel-text="Fechar"
                                    hide-offset-dates>
                                    <template #clear-icon="{ clear }">
                                        <i
                                            class="v-icon notranslate v-theme--theme-light v-icon--size-default v-icon--clickable input-slot-image mr-4"
                                            role="button"
                                            aria-hidden="false"
                                            tabindex="0"
                                            @click="clear">
                                            <svg
                                                class="svg-inline--fa fa-circle-xmark"
                                                aria-hidden="true"
                                                focusable="false"
                                                data-prefix="fas"
                                                data-icon="circle-xmark"
                                                role="img"
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 512 512">
                                                <path
                                                    class=""
                                                    :fill="errorMessage('start_at') ? '#B00020' : 'currentColor'"
                                                    d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z">
                                                </path>
                                            </svg>
                                        </i>
                                    </template>
                                </vue-date-picker>

                                <span class="fs-12x px-4" style="color: #B00020"> {{ errorMessage('start_at') }}</span>
                            </div>
                        </v-col>

                        <v-col cols="12" md="6" class="pb-1">
                            <v-number-input
                                v-model="form.start_minutes_additions"
                                label="Acréscimos iniciais"
                                :min="0"
                                class="w-100 mb-1"
                                density="small"
                                color="orange-darken-4"
                                hide-details
                                required variant="outlined"
                                @update:modelValue="form.start_minutes_additions = $event">
                                <template #increment="{ props }">
                                    <div class="d-flex align-center pa-2">
                                        <v-icon icon="fas fa-plus" size="x-small" color="grey-darken-2" v-bind="props" class="cursor-pointer"></v-icon>
                                    </div>
                                </template>

                                <template #decrement="{ props }">
                                    <div class="d-flex align-center pa-2">
                                        <v-icon icon="fas fa-minus" size="x-small" color="grey-darken-2" v-bind="props" class="cursor-pointer"></v-icon>
                                    </div>
                                </template>
                            </v-number-input>
                        </v-col>
                    </v-row>
                </v-col>

                <v-col cols="12" md="6">
                    <v-row>
                        <v-col cols="12" md="6" class="pb-1">
                            <div class="position-relative w-100">
                                <label
                                    :class="['dp__label fs-12x position-absolute px-1', { 'dp__label_invalid': Boolean(errorMessage('end_at')) }]">
                                    Data fim *
                                </label>

                                <vue-date-picker
                                    v-model="form.end_at"
                                    model-type="yyyy-MM-dd HH:mm:ss"
                                    format="dd/MM/yyyy HH:mm:ss"
                                    locale="pt-br"
                                    placeholder="Data fim *"
                                    :state="!Boolean(errorMessage('end_at'))"
                                    :min-date="form.start_at || new Date()"
                                    month-name-format="long"
                                    select-text="Selecionar"
                                    cancel-text="Fechar"
                                    hide-offset-dates>
                                    <template #clear-icon="{ clear }">
                                        <i
                                            class="v-icon notranslate v-theme--theme-light v-icon--size-default v-icon--clickable input-slot-image mr-4"
                                            role="button"
                                            aria-hidden="false"
                                            tabindex="0"
                                            @click="clear">
                                            <svg
                                                class="svg-inline--fa fa-circle-xmark"
                                                aria-hidden="true"
                                                focusable="false"
                                                data-prefix="fas"
                                                data-icon="circle-xmark"
                                                role="img"
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 512 512">
                                                <path
                                                    class=""
                                                    :fill="errorMessage('end_at') ? '#B00020' : 'currentColor'"
                                                    d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z">
                                                </path>
                                            </svg>
                                        </i>
                                    </template>
                                </vue-date-picker>

                                <span class="fs-12x px-4" style="color: #B00020"> {{ errorMessage('end_at') }}</span>
                            </div>
                        </v-col>

                        <v-col cols="12" md="6" class="pb-1">
                            <v-number-input
                                v-model="form.end_minutes_additions"
                                label="Acréscimos finais"
                                :min="0"
                                class="w-100 mb-1"
                                density="small"
                                color="orange-darken-4"
                                hide-details
                                required variant="outlined"
                                @update:modelValue="form.end_minutes_additions = $event">
                                <template #increment="{ props }">
                                    <div class="d-flex align-center pa-2">
                                        <v-icon icon="fas fa-plus" size="x-small" color="grey-darken-2" v-bind="props" class="cursor-pointer"></v-icon>
                                    </div>
                                </template>

                                <template #decrement="{ props }">
                                    <div class="d-flex align-center pa-2">
                                        <v-icon icon="fas fa-minus" size="x-small" color="grey-darken-2" v-bind="props" class="cursor-pointer"></v-icon>
                                    </div>
                                </template>
                            </v-number-input>
                        </v-col>
                    </v-row>
                </v-col>
            </v-row>

            <v-row>
                <v-col cols="12" md="6" class="py-1">
                    <v-file-input
                        v-model="form.banner"
                        label="Banner"
                        density="small"
                        variant="outlined"
                        :error-messages="errorMessage('banner')"
                        color="orange-darken-4"
                        placeholder="Banner"
                        autocomplete="false"
                        accept="image/png, image/jpeg"
                        show-size
                        hint="Faça o upload do banner de divulgação. O banner será enviado por e-mail em anexo."
                        persistent-hint
                        prepend-icon=""
                        :prepend-inner-icon="icon('fas fa-paperclip', 'fs-16x')"
                        clearable>
                    </v-file-input>
                </v-col>
            </v-row>

            <v-row>
                <v-col cols="12" class="py-1">
                    <span>Notifique por email usuários para participar dessa webaula</span>

                    <v-radio-group :center-affix="false" class="mt-1" v-model="form.type_notification" density="compact">
                        <v-radio label="Notificar todos no sistema" color="orange-darken-4" class="fs-12x" value="all"></v-radio>
                        <v-radio label="Notificar participantes por cidade" color="orange-darken-4" class="fs-12x" value="cities"></v-radio>
                        <v-radio label="Notificar um grupo seleto de participantes" color="orange-darken-4" class="fs-12x" value="group"></v-radio>
                        <v-radio label="Não enviar notificação" color="orange-darken-4" class="fs-12x" value="none"></v-radio>
                    </v-radio-group>

                    <v-autocomplete
                        v-if="form.type_notification === 'cities'"
                        label="Cidades *"
                        v-model="form.cities_to_notify"
                        :items="cityStore.list"
                        :item-title="(city) => `&nbsp; ${city.name}`"
                        :item-value="(city) => city.uuid"
                        :error-messages="errorMessage('cities_to_notify')"
                        density="small"
                        no-data-text="Nenhum item encontrado."
                        required variant="outlined"
                        color="orange-darken-4"
                        placeholder="Cidades"
                        autocomplete="false"
                        multiple
                        clearable
                        clear-on-select>
                    </v-autocomplete>

                    <div v-if="form.type_notification === 'group'" class="position-relative">
                        <v-textarea
                            label="Emails a serem notificados de uma nova agenda de webaula *"
                            v-model="form.select_group_emails"
                            rows="3"
                            :error-messages="emailsNotificationMessageErrors.join(' ') || errorMessage('select_group_emails')"
                            density="small"
                            required variant="outlined"
                            color="orange-darken-4"
                            hint="Emails que receberão a notificação da webaula. E-mails devem ser separados por espaço."
                            persistent-hint
                            placeholder="Emails a serem notificados de uma nova agenda de webaula"
                            autocomplete="false"
                            clearable
                            @input="handleEmailsForNotification"
                            @click:clear="presetEmailsForNotification">
                        </v-textarea>

                        <v-btn
                            icon="fas fa-check"
                            type="button"
                            class="position-absolute"
                            style="right: -15px; bottom: 15px;"
                            size="x-small"
                            :color="form.select_group_emails ? 'teal-lighten-1' : ''"
                            :disabled="!form.select_group_emails"
                            @click.prevent.stop="presetEmailsForNotification">
                        </v-btn>
                    </div>

                    <div v-if="form.type_notification === 'group'" class="mt-4">
                        <span>E-mails de participantes a serem notificados de uma nova webaula</span>

                        <v-sheet v-if="emailNotificationList.length" border="thin" class="d-flex flex-wrap ga-2 pa-2">
                            <template v-for="(email, index) in emailNotificationList" :key="index">
                                <v-chip
                                    label
                                    variant="flat"
                                    :color="emailsNotificationWithErrors.includes(email) ? 'red-darken-1' : ''"
                                    closable>
                                    <template #default>
                                        {{ email }}
                                    </template>

                                    <template #close>
                                        <v-icon icon="fas fa-circle-xmark" @click.prevent.stop="purgeEmailsForNotification(index)"></v-icon>
                                    </template>
                                </v-chip>
                            </template>
                        </v-sheet>

                        <v-sheet v-else border="thin" class="d-flex flex-wrap ga-2 pa-2">
                            Nenhum email encontrado na lista
                        </v-sheet>
                    </div>
                </v-col>
            </v-row>
        </v-sheet>

        <div class="d-flex justify-end ga-2 mt-8">
            <v-btn
                type="submit"
                :prepend-icon="icon('fas fa-check')"
                variant="flat"
                color="teal-lighten-1"
                rounded="sm"
                class="font-weight-bold text-none"
                size="small"
                @click="">
                Salvar
            </v-btn>
        </div>
    </v-form>

</template>

<script setup>
import { onMounted, ref, useTemplateRef, watch } from 'vue'
import { useRouter } from 'vue-router'
import { errorMessage, presetForm, presetFilter, truncateText } from '@/helpers'
import useIcon from '@/composables/useIcon'

import { useAppStore } from '@/stores/appStore'
import { useEventStore } from '@/stores/eventStore'
import { useDescStore } from '@/stores/descStore'
import { useStateStore } from '@/stores/stateStore'
import { useCityStore } from '@/stores/cityStore'

const appStore = useAppStore()
const stateStore = useStateStore()
const cityStore = useCityStore()
const eventStore = useEventStore()
const descsStore = useDescStore()

const router = useRouter()
const { icon } = useIcon()
const intersectEl = useTemplateRef('intersect-element')
const timeoutId = ref()
const errors = ref([])
const emailSummaryList = ref([])
const emailsSummaryMessageErrors = ref([])
const emailsSummaryWithErrors = ref([])
const emailNotificationList = ref([])
const emailsNotificationMessageErrors = ref([])
const emailsNotificationWithErrors = ref([])
const form = ref({
    name: null,
    organization: null,
    room_link: null,
    descs: null,
    start_at: null,
    start_minutes_additions: 30,
    end_at: null,
    end_minutes_additions: 30,
    type_notification: 'none',
    cities_to_notify: null,
    select_group_emails: null,
    summary_emails: null,
    type_event: 'Webaulas/palestras',
    banner: null,
})
const filter = ref({
    biremeCode: null,
})

/* onMounted */
onMounted(() => {
    appStore.pageTitle = 'Webaulas'
    eventStore.eventTitle = 'Novo cadastro'
    descsStore.list = []
})

/* watch */
watch(() => form.value.type_notification, (type_notification) => {
    form.value.select_group_emails = null
    emailNotificationList.value = []
    emailsNotificationWithErrors.value = []
    emailsNotificationMessageErrors.value = []
    form.value.cities_to_notify = null

    if (type_notification === 'cities' && !cityStore.list.length) {
        loadState()
    }
})

watch([() => form.value.start_minutes_additions, () => form.value.end_minutes_additions], ([start_minutes, end_minutes]) => {
    if (start_minutes === null) {
        form.value.start_minutes_additions = 30
    }

    if (end_minutes === null) {
        form.value.end_minutes_additions = 30
    }
})

/* functions */
function searchDescs(value) {
    clearTimeout(timeoutId.value)

    if (!value) {
        return
    }

    if (value && value.length >= 4) {
        timeoutId.value = setTimeout(() => loadDescs(value), 1200)
    }
}

async function loadState() {
  const reponse = await stateStore.index()

  if (reponse.ok) {
    const data = reponse.data
    stateStore.list = data || []
    const state = stateStore.list.find((state) => state.acronym === 'MS')

    loadCity(state.uuid)
  }
}

async function loadCity(state, selectedCity = null) {
  if (state) {
    const reponse = await cityStore.index({state: state})

    if (reponse.ok) {
      const data = reponse.data
      form.value.establishment = null
      cityStore.list = data || []
    }

    return
  }

  cityStore.list = []
}

async function loadDescs(value, page = 1) {
    const payload = presetFilter(value, filter.value)
    const reponse = await descsStore.index({ page, limit: 15, orderBy: 'bireme_code', direction: 'asc', ...payload })

    if (reponse.ok) {
        const data = reponse.data
        let dataFiltered = data.filter((item) => !descsStore.list.some((descItem) => item.uuid === descItem.uuid))
        descsStore.list.push(...dataFiltered)

        if (descsStore.list.length >= 15 && data.length) {
            setTimeout(() => {
                const intersectParentElement = intersectEl.value.parentElement
                const childrenEl = intersectParentElement.querySelectorAll('div.v-list-item.v-list-item--link')
                const observerEl = Array.from(childrenEl).find((_, index, list) => index === (list.length - 1))
                const intersectionObserver = new IntersectionObserver(onObserver(value, page), { root: intersectParentElement, rootMargin: '0px', threshold: 0.3 })

                if (observerEl) {
                  intersectionObserver.observe(observerEl)
                }
            }, 1200)
        }
    }
}

function onObserver(value, page) {
    return (entries, observer) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
            loadDescs(value, ++page)
            observer.unobserve(entry.target)
        }
      })
    }
}

async function save() {
    const formData = new FormData()
    const payload = presetForm(form.value)
    payload.summary_emails = payload.summary_emails?.length ? JSON.stringify(payload.summary_emails.split(' ')) : null
    payload.cities_to_notify = payload.cities_to_notify?.length ? JSON.stringify(payload.cities_to_notify) : null
    payload.select_group_emails = payload.select_group_emails?.length ? JSON.stringify(payload.select_group_emails.split(' ')) : null

    for (const key in payload) {
        formData.append(key, payload[key]);
    }

    const response = await eventStore.store(payload)

    if (response.status === 200 || response.status === 201) {
        router.push({ name: 'webs.view' })
    }

    if (response.status === 422) {
        errors.value = response.data.errors
    }
}

function presetEmailsForSummary() {
    emailsSummaryWithErrors.value = []
    emailsSummaryMessageErrors.value = []
    emailSummaryList.value = []
    emailSummaryList.value = form.value.summary_emails.split(' ').reduce((acc, email) => acc = (email.trim()) ? [...acc, email.trim()] : acc, [])

    reoderEmailsContentForParagraphs(emailSummaryList)
    purgeDuplicateEmails(emailSummaryList)
    validateEmail(emailSummaryList, emailsSummaryWithErrors, emailsSummaryMessageErrors)

    form.value.summary_emails = emailSummaryList.value.join(' ')
}

function handleEmailsForSummary() {
    if (!form.value.summary_emails.length) {
        presetEmailsForSummary()
    }
}

function purgeEmailsForSummary(index) {
    emailSummaryList.value.splice(index, 1)
    form.value.summary_emails = emailSummaryList.value.join(' ')
}

function presetEmailsForNotification() {
    emailsNotificationWithErrors.value = []
    emailsNotificationMessageErrors.value = []
    emailNotificationList.value = []
    emailNotificationList.value = form.value.select_group_emails.split(' ').reduce((acc, email) => acc = (email.trim()) ? [...acc, email.trim()] : acc, [])

    reoderEmailsContentForParagraphs(emailNotificationList)
    purgeDuplicateEmails(emailNotificationList)
    validateEmail(emailNotificationList, emailsNotificationWithErrors, emailsNotificationMessageErrors)

    form.value.select_group_emails = emailNotificationList.value.join(' ')
}

function handleEmailsForNotification() {
    if (!form.value.select_group_emails.length) {
        presetEmailsForNotification()
    }
}

function purgeEmailsForNotification(index) {
    emailNotificationList.value.splice(index, 1)
    form.value.select_group_emails = emailNotificationList.value.join(' ')
}

function reoderEmailsContentForParagraphs(list) {
    const emailsNotificationCount = list.value.length
    const emailsNotificationHasParagraphs = list.value[0]?.indexOf('\n')

    if (emailsNotificationCount === 1 && emailsNotificationHasParagraphs) {
        list.value = list.value[0].split('\n')
    }

    list.value.forEach((email, index) => {
        const emailHasParagraphs = email.indexOf('\n')

        if (emailHasParagraphs >= 1) {
            const emailList = email.split('\n')

            emailList.forEach((selfEmail) => list.value.push(selfEmail))
            list.value.splice(index , 1)
        }
    })
}

function purgeDuplicateEmails(list) {
    list.value = list.value.filter((item, index) => list.value.indexOf(item) === index)
}

function validateEmail(list, emailWithErrors, emailsMessageErrors) {
    const emailRegex = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i

    list.value.forEach((email) => {
        if (!emailRegex.test(email.trim())) {
            emailWithErrors.value.push(email)
            emailsMessageErrors.value.push(`${email} é um email inválido.`)
        }
    })
}
</script>