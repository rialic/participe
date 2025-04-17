<template>
    <v-container class="h-100">
        <v-row align="center" justify="center" class="h-100">
            <v-col cols="12" sm="10">
                <v-sheet elevation="1" border="thin" rounded>
                    <v-card variant="flat" class="pa-4">
                        <v-row>
                            <v-col col="12" :md="`${mobile || !participantList.length ? '12' : 7 }`">
                                <div class="mb-8">
                                    <h3 class="text-grey-darken-3">Webaulas</h3>

                                    <span class="fs-14x font-weight-bold text-grey-darken-2">
                                      Escolha na lista abaixo o tema que gostaria de participar e inscreva-se para acessar a webaula e ter acesso ao certificado
                                    </span>
                                </div>

                                <div class="d-flex flex-wrap justify-end mt-6 mb-4 ga-4">
                                    <template v-if="participantList.length">
                                      <v-btn
                                          :prepend-icon="icon('fas fa-plus')"
                                          variant="flat"
                                          color="primary"
                                          rounded="sm"
                                          class="font-weight-bold text-none fs-13x"
                                          size="small"
                                          @click="showSignUpParticipantModal = true">
                                          Inserir participante
                                      </v-btn>
                                    </template>

                                    <v-btn
                                      :prepend-icon="icon('far fa-file-lines')"
                                      variant="flat"
                                      color="primary"
                                      rounded="sm"
                                      class="font-weight-bold text-none fs-13x"
                                      @click="router.push({ name: 'guest.certificates' })"
                                      size="small">
                                        Certificados
                                    </v-btn>

                                    <template v-if="participantList.length">
                                      <v-btn
                                        :append-icon="icon('fas fa-circle-right')"
                                        variant="flat"
                                        color="teal-lighten-1"
                                        rounded="sm"
                                        class="font-weight-bold text-none text-white pulse-teal-lighten-1 fs-13x"
                                        size="small"
                                        @click="enterRoom">
                                        Entrar na sala
                                      </v-btn>
                                    </template>
                                </div>

                                <v-card v-if="eventList.length" border="thin" elevation="0" rounded="md">
                                    <v-data-table
                                        :headers="eventsDtheaders"
                                        :items="eventList"
                                        item-value="theme"
                                        density="comfortable"
                                        v-model="selectedEvent"
                                        select-strategy="single"
                                        show-select
                                        hide-default-footer
                                        :hide-default-header="mobile"
                                        :class="{ 'mobile': mobile}"
                                        hover="true">
                                        <template #top>
                                            <v-toolbar density="small" class="px-2" color="grey-lighten-5">
                                                <h4>Eventos disponíveis</h4>
                                            </v-toolbar>
                                        </template>

                                        <template v-if="mobile" #item="{ item }">
                                          <tr :class="{'bg-orange-lighten-5': isSelected(item), }" @click="onClickRowEvent(item)">
                                            <td>
                                              <div class="d-flex flex-column">
                                                <v-checkbox-btn
                                                  :model-value="isSelected(item)"
                                                  color="orange-darken-4"
                                                  density="compact"
                                                  @update:model-value="toggleSelect(item)">
                                                  <span class="font-weight-bold me-2">Selecione</span>
                                                </v-checkbox-btn>

                                                <div class="d-flex pa-0 flex-wrap ga-6">
                                                  <div data-label="Tema" class="font-weight-bold">{{ item.theme }}</div>
                                                  <div data-label="Início">{{ item.start }}</div>
                                                  <div data-label="Fim">{{ item.end }}</div>
                                                </div>
                                              </div>
                                            </td>
                                          </tr>
                                        </template>

                                        <template v-if="!mobile" #headers="{ columns }">
                                            <tr>
                                                <template v-for="column in columns" :key="column.key">
                                                    <th>
                                                        <span class="font-weight-bold">{{ column.title }}</span>
                                                    </th>
                                                </template>
                                            </tr>
                                        </template>

                                        <template v-if="!mobile" #item="{ item }">
                                            <tr :class="['cursor-pointer', {'bg-orange-lighten-5': isSelected(item)}]" @click="onClickRowEvent(item)">
                                                <td>
                                                    <v-checkbox-btn
                                                        :model-value="isSelected(item)"
                                                        color="orange-darken-4"
                                                        density="compact"
                                                        @update:model-value="toggleSelect(item)">
                                                    </v-checkbox-btn>
                                                </td>

                                                <td>
                                                    <span class="font-weight-bold">{{ item.theme }}</span>
                                                </td>

                                                <td>
                                                    <span>{{ item.start }}</span>
                                                </td>

                                                <td>
                                                    <span>{{ item.end }}</span>
                                                </td>
                                            </tr>
                                        </template>
                                    </v-data-table>
                                </v-card>

                                <no-content-found v-if="showNoContentFound"></no-content-found>
                            </v-col>

                            <v-col v-if="participantList.length" col="12" md="5" class="d-none d-lg-block container">
                                <div class="d-flex justify-center h-100">

                                  <v-card elevation="0" border="thin" width="100%">
                                      <div class="d-flex justify-center mt-6 mb-4">
                                        <v-btn
                                            :prepend-icon="icon('fas fa-plus')"
                                            variant="flat"
                                            color="orange-darken-4"
                                            rounded="sm"
                                            class="font-weight-bold text-none fs-13x"
                                            size="small"
                                            @click="showSignUpParticipantModal = true">
                                            Inserir participante
                                        </v-btn>
                                      </div>

                                      <v-card elevation="0" rounded="md" class="mx-4">
                                        <v-data-table
                                          :headers="participantsDtHeader"
                                          :items="participantList"
                                          item-value="theme"
                                          density="comfortable"
                                          v-model="selectedEvent"
                                          hide-default-footer
                                          hover="true">
                                          <template #top>
                                              <v-toolbar density="small" class="px-2" color="grey-lighten-4">
                                                  <h4>Participantes</h4>
                                              </v-toolbar>
                                          </template>

                                          <template #headers="{ columns }">
                                              <tr>
                                                  <template v-for="column in columns" :key="column.key">
                                                      <th :class="{ 'text-right': column.title === 'Ações' }">
                                                          <span class="font-weight-bold">{{ column.title }}</span>
                                                      </th>
                                                  </template>
                                              </tr>
                                          </template>

                                          <template #item="{ item, index }">
                                              <tr>
                                                  <td>
                                                      <span class="font-weight-bold">{{ item.name }}</span>
                                                  </td>

                                                  <td class="text-right">
                                                    <v-btn
                                                        variant="plain"
                                                        size="x-small"
                                                        density="compact"
                                                        icon="fas fa-trash"
                                                        class="px-0"
                                                        @click="onRemoveParticipant(event, item, index)">
                                                    </v-btn>
                                                  </td>
                                              </tr>
                                          </template>
                                        </v-data-table>
                                      </v-card>
                                    </v-card>
                                </div>
                            </v-col>
                          </v-row>

                          <v-row v-if="participantList.length">
                            <v-col col="12" class="d-block d-lg-none container">
                                <div class="d-flex justify-center h-100">
                                      <v-card elevation="0" rounded="md" border="thin" width="100%">
                                        <v-data-table
                                          :headers="participantsDtHeader"
                                          :items="participantList"
                                          item-value="theme"
                                          density="comfortable"
                                          v-model="selectedEvent"
                                          hide-default-footer
                                          hover="true">
                                            <template #top>
                                                <v-toolbar density="small" class="px-2" color="grey-lighten-4">
                                                    <h4>Participantes</h4>
                                                </v-toolbar>
                                            </template>

                                            <template #headers="{ columns }">
                                                <tr>
                                                    <template v-for="column in columns" :key="column.key">
                                                        <th :class="{ 'text-right': column.title === 'Ações' }">
                                                            <span class="font-weight-bold">{{ column.title }}</span>
                                                        </th>
                                                    </template>
                                                </tr>
                                            </template>

                                            <template #item="{ item, index }">
                                                <tr>
                                                    <td>
                                                        <span class="font-weight-bold">{{ item.name }}</span>
                                                    </td>

                                                    <td class="text-right">
                                                      <v-btn
                                                        variant="plain"
                                                        size="x-small"
                                                        density="compact"
                                                        icon="fas fa-trash"
                                                        class="px-0"
                                                        @click="onRemoveParticipant(event, item, index)">
                                                      </v-btn>
                                                    </td>
                                                </tr>
                                            </template>
                                          </v-data-table>
                                      </v-card>
                                </div>
                            </v-col>
                        </v-row>
                    </v-card>
                </v-sheet>
            </v-col>
        </v-row>
    </v-container>

    <v-dialog v-model="showSignUpParticipantModal" max-width="1000" @afterLeave="onCloseSignUpParticipantModal">
      <v-card>
        <template #prepend>
          <v-icon icon="fas fa-user" size="small" class="text-grey-darken-2"></v-icon>
        </template>

        <template #title>
          <span class="fs-20x text-grey-darken-3 font-weight-bold">Cadastro de Participante</span>
        </template>

        <v-card-text>
          <v-row>
            <v-col cols="12" class="pt-2 pb-1" md="6">
              <v-text-field
                label="CPF *"
                v-model="form.cpf"
                :rules="[rules.cpf]"
                :error-messages="errorMessage('cpf')"
                density="small"
                required variant="outlined"
                color="orange-darken-4"
                placeholder="CPF"
                autocomplete="false"
                v-maska:[form.cpf]="'###.###.###-##'"
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
            </v-col>
          </v-row>

          <v-row>
            <v-col v-if="showAllInputs" cols="12" md="4" class="py-1">
              <v-text-field
                label="Nome *"
                v-model="form.name"
                :error-messages="errorMessage('name')"
                density="small"
                required variant="outlined"
                color="orange-darken-4"
                placeholder="Nome"
                autocomplete="false"
                clearable>
              </v-text-field>
            </v-col>

            <v-col v-if="showAllInputs || showEmailSexInputs" cols="12" md="4" class="py-1">
              <v-select
                label="Sexo *"
                v-model="form.sex"
                :items="['Masculino', 'Feminino']"
                :error-messages="errorMessage('sex')"
                density="small"
                required variant="outlined"
                color="orange-darken-4"
                placeholder="Sexo"
                autocomplete="false"
                clearable>
              </v-select>
            </v-col>

            <v-col v-if="showAllInputs || showEmailSexInputs" cols="12" md="4" class="py-1">
              <v-text-field
                label="Email *"
                v-model="form.email"
                :error-messages="errorMessage('email')"
                density="small"
                hint="Informe seu melhor e-mail"
                :persistent-hint="true"
                required variant="outlined"
                color="orange-darken-4"
                placeholder="Email"
                autocomplete="false"
                clearable>
              </v-text-field>
            </v-col>

            <v-col v-if="showAllInputs || showEstablishmentsInputs" cols="12">
              <v-autocomplete
                label="Ocupação *"
                v-model="form.cbo"
                :items="cboStore.list"
                :error-messages="errorMessage('cbo')"
                hint="Digite o nome da sua profissão ou código CBO"
                density="small"
                required variant="outlined"
                no-data-text="Nenhum item encontrado."
                persistent-hint="true"
                color="orange-darken-4"
                placeholder="Ocupação"
                autocomplete="false"
                :item-title="(cbo) => `${cbo.name} - CBO: ${cbo.code}`"
                item-value="uuid"
                clearable>
              </v-autocomplete>
            </v-col>

            <v-col v-if="showAllInputs || showEstablishmentsInputs" cols="12" md="6">
              <v-select
                label="UF"
                v-model="form.state"
                :items="stateStore.list"
                density="small"
                required variant="outlined"
                color="orange-darken-4"
                placeholder="UF"
                autocomplete="false"
                item-title="name"
                item-value="uuid"
                @update:modelValue="loadCity"
                clearable>
              </v-select>
            </v-col>

            <v-col v-if="showAllInputs || showEstablishmentsInputs" cols="12" md="6">
              <v-autocomplete
                label="Cidade *"
                v-model="form.city"
                :items="cityStore.list"
                :error-messages="errorMessage('city')"
                density="small"
                no-data-text="Nenhum item encontrado."
                required variant="outlined"
                color="orange-darken-4"
                placeholder="Cidade"
                autocomplete="false"
                item-title="name"
                item-value="uuid"
                @update:modelValue="loadEstablishment"
                clearable>
              </v-autocomplete>
            </v-col>

            <v-col v-if="showAllInputs || showEstablishmentsInputs" cols="12">
              <v-autocomplete
                label="Estabelecimento *"
                v-model="form.establishment"
                :items="establishmentStore.list"
                :error-messages="errorMessage('establishment')"
                density="small"
                no-data-text="Nenhum item encontrado."
                required variant="outlined"
                color="orange-darken-4"
                placeholder="Estabelecimento"
                autocomplete="false"
                :item-title="(establishment) => `${establishment.name} - CNES: ${establishment.cnes}`"
                item-value="uuid"
                clearable>
              </v-autocomplete>
            </v-col>
          </v-row>

          <small class="text-caption font-weight-bold">* Campos obrigatórios</small>
        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions>
          <v-spacer></v-spacer>

          <v-btn
            v-if="showAllInputs || showEmailSexInputs || showEstablishmentsInputs"
            :prepend-icon="icon('fas fa-circle-check')"
            color="teal-lighten-1"
            class="font-weight-bold text-none fs-13x"
            variant="tonal"
            size="small"
            @click="saveParticipant">
            Salvar
          </v-btn>

          <v-btn
            class="font-weight-bold text-none fs-13x"
            color="black"
            variant="tonal"
            size="small"
            @click="onCloseSignUpParticipantModal">
            Fechar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="showDeleteParticipantModal" max-width="1000">
      <v-card>
        <template #prepend>
          <v-icon icon="fas fa-user-xmark" size="small" class="text-grey-darken-2"></v-icon>
        </template>

        <template #title>
          <span class="fs-20x text-grey-darken-3 font-weight-bold">Excluir Participante</span>
        </template>

        <v-card-text class="d-flex flex-column align-center ga-2">
            <h4>Tem certeza que deseja excluir o participante?</h4>

            <h4>{{ participant.name }}</h4>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>

          <v-btn
            :prepend-icon="icon('fas fa-circle-check')"
            color="teal-lighten-1"
            class="font-weight-bold"
            variant="tonal"
            size="small"
            @click="removeParticipant(participant.index)">
            Sim
          </v-btn>

          <v-btn
            :prepend-icon="icon('fas fa-circle-xmark')"
            class="font-weight-bold"
            color="black"
            variant="tonal"
            size="small"
            @click="showDeleteParticipantModal = false">
            Não
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
</template>

<script setup>
import { cpfValidated, maskDate, errorMessage } from '@/helpers'
import { computed, onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useDisplay } from 'vuetify'
import useIcon from '@/composables/useIcon'

import { useAlertStore } from '@/stores/alertStore'
import { useCboStore } from '@/stores/cboStore'
import { useCityStore } from '@/stores/cityStore'
import { useEstablishmentStore } from '@/stores/establishmentStore'
import { useEventStore } from '@/stores/eventStore'
import { useParticipantStore } from '@/stores/participantStore'
import { useStateStore } from '@/stores/stateStore'

const participantStore = useParticipantStore()
const eventStore = useEventStore()
const stateStore = useStateStore()
const cityStore = useCityStore()
const establishmentStore = useEstablishmentStore()
const cboStore = useCboStore()
const alertStore = useAlertStore()
const router = useRouter()
const { icon } = useIcon()

const { mobile } = useDisplay()
const showSignUpParticipantModal = ref(false)
const showDeleteParticipantModal = ref(false)
const showAllInputs = ref(false)
const showEmailSexInputs = ref(false)
const showEstablishmentsInputs = ref(false)
const showNoContentFound = ref(false)
const enableSearchParticipant = ref(false)
const selectedEvent = ref([])
const participant = ref()
const errors = ref([])
const participantsDtHeader = [{ title: 'Nome', key: 'name' }, { title: 'Ações', key: 'actions'}]
const participantList = ref([])
const eventsDtheaders = [{ title: 'Tema', key: 'theme',  },  { title: 'Início', key: 'start',  },  { title: 'Fim', key: 'end',  }]
const eventList = ref([])

const form = ref({
  uuid: null,
  cpf: null,
  name: null,
  email: null,
  sex: null,
  cbo: null,
  state: null,
  city: null,
  establishment: null,
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

      enableSearchParticipant.value = false
      showAllInputs.value = false
      showEmailSexInputs.value = false
      showEstablishmentsInputs.value = false
  }
})

/* onMounted */
onMounted(() => {
  selectedEvent.value = null

  loadEvent()
  loadState()
  loadCbo()
})

/* computed */
const isSelected = computed(() => {
    return (item) => {
        if (selectedEvent.value?.length) {
            return !!selectedEvent.value.find((event) => event.theme === item.theme)
        }

        return false
  }
})

const toggleSelect = computed(() => {
    return (item) => {
        selectedEvent.value = [item]
  }
})


/* functions */
function onClickRowEvent(item) {
  showSignUpParticipantModal.value = participantList.value.length <= 0

  toggleSelect.value(item)
}

function onCloseSignUpParticipantModal() {
  showSignUpParticipantModal.value = false
  form.value.cpf = null

  clearParticipantForm()
}

async function saveParticipant() {
  const response = await participantStore[!form.value.uuid ? 'store' : 'update'](form.value)

  if (response.ok) {
    const data = response.data
    form.value.cpf = null
    showSignUpParticipantModal.value = false

    participantList.value.push({ name: data.name, uuid: data.uuid })
    alertStore.setTypeAlert('success')
    alertStore.setMessage('Participante inserido com sucesso.')
    clearParticipantForm()

    return
  }

  if (response.status === 422) {
    errors.value = response.data.errors
  }
}

function removeParticipant(index) {
  showDeleteParticipantModal.value = false
  alertStore.showAlert = true

  participantList.value.splice(index, 1)
  alertStore.setTypeAlert('success')
  alertStore.setMessage('Participante excluído com sucesso.')
}

async function onSearchParticipant() {
    const response = await participantStore.show(form.value.cpf)

    clearParticipantForm()

    if (response.ok) {
      const data = response.data
      showEmailSexInputs.value = data?.email?.includes('@email.com') || !data?.email
      showEstablishmentsInputs.value = !data?.establishment?.uuid
      showAllInputs.value = !data
      form.value.state = (showEstablishmentsInputs.value || !data) ? stateStore.list.find((state) => state.acronym === 'MS') : null

      if (!data) {
        return
      }

      if (!showEmailSexInputs.value && !showEstablishmentsInputs.value && data.uuid) {
        const isParticipantInList = !!participantList.value.find((participant) => participant.uuid === data.uuid)
        alertStore.showAlert = true
        showSignUpParticipantModal.value = false
        form.value.cpf = null

        if(!isParticipantInList) {
          participantList.value.push({ name: data.name, uuid: data.uuid })
        }

        alertStore.setTypeAlert('success')
        alertStore.setMessage('Participante inserido com sucesso.')

        return
      }

      form.value.uuid = data.uuid
      form.value.name = data.name?.toUpperCase()
      form.value.sex = data.email?.includes('@email.com') ? null : form.value.sex
      form.value.email = data.email?.includes('@email.com') ? null : data.email
      form.value.cbo = data.cbo?.uuid
      form.value.state = data.establishment?.city.state.uuid || form.value.state.uuid

      await loadCity(form.value.state, data.establishment?.city.uuid)
      await loadEstablishment(data.establishment?.city.uuid, data.establishment?.uuid)
    }
}

function onRemoveParticipant(event, selectedPartipant, index) {
  showDeleteParticipantModal.value = true

  participant.value = { ...selectedPartipant, index}
}

async function enterRoom() {
  const response = await eventStore.syncParticipants({
    uuid: selectedEvent.value[0].uuid,
    participants: participantList.value.map((participant) => participant.uuid)
  })

  if(response.ok && response.status === 201) {
    alertStore.showAlert = false
    window.location.href = selectedEvent.value[0].room_link
  }
}

function clearParticipantForm() {
  showEmailSexInputs.value = false
  showEstablishmentsInputs.value = false
  showAllInputs.value = false
  errors.value = []

  for (const attribute in form.value) {
    if (attribute === 'cpf') {
      continue
    }

    form.value[attribute] = null
  }
}

async function loadEvent() {
  const response = await eventStore.index({eventsAvailables: 'today'})

  if (response.ok) {
    const data = response.data
    eventStore.list = data || []
    eventList.value = eventStore.list.map((event) => ({
        uuid: event.uuid,
        theme: event.name,
        start: maskDate(event.start_at),
        end: maskDate(event.end_at),
        room_link: event.room_link
    }))

    if (!eventStore.list.length) {
      showNoContentFound.value = true
    }
  }
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
    const state = stateStore.list.find((state) => state.acronym === 'MS')

    loadCity(state.uuid)
  }
}

async function loadCity(state, selectedCity = null) {
  if (state) {
    const reponse = await cityStore.index({state: state})

    if (reponse.ok) {
      const data = reponse.data
      form.value.city = selectedCity
      form.value.establishment = null
      cityStore.list = data || []
    }

    return
  }

  form.value.city = null
  cityStore.list = []
}

async function loadEstablishment(city, selectedEstablishment) {
  if (city) {
    const reponse = await establishmentStore.index({city: city})

    if (reponse.ok) {
      const data = reponse.data
      form.value.establishment = selectedEstablishment
      establishmentStore.list = data || []
    }

    return
  }

  form.value.establishment = null
  establishmentStore.list = []
}
</script>

<style>
.container {
  width: 100%;
  height: auto;
  background:
    /* Diagonal slices */
    radial-gradient(
      circle at 100% 50%,
      #ff9900 0% 2%,
      #ffa47b 3% 5%,
      transparent 6%
    ),
    /* Offset dots */
      radial-gradient(
        circle at 0% 50%,
        #ff9900 0% 2%,
        #ffa47b 3% 5%,
        transparent 6%
      ),
    /* Wave-like pattern */
      radial-gradient(ellipse at 50% 0%, #ff8667 0% 3%, transparent 4%) 10px 10px,
    /* Scattered elements */
      radial-gradient(
        circle at 50% 50%,
        #ffa47b 0% 1%,
        #ff9900 2% 3%,
        #ff8667 4% 5%,
        transparent 6%
      ) 20px 20px,
    /* Background texture */
      repeating-linear-gradient(
        45deg,
        #ffffff,
        #ffffff 10px,
        #fff9f4 10px,
        #fff9f4 20px
      );
  background-size:
    50px 50px,
    50px 50px,
    40px 40px,
    60px 60px,
    100% 100%;
  animation: shift 15s linear infinite;
}

@keyframes shift {
  0% {
    background-position:
      0 0,
      0 0,
      10px 10px,
      20px 20px,
      0 0;
  }
  100% {
    background-position:
      50px 50px,
      -50px -50px,
      60px 60px,
      80px 80px,
      0 0;
  }
}
</style>