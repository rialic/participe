<template>
<v-dialog v-model="certificateStore.showRatingModal" max-width="1000">
      <v-card subtitle="Gostariamos de saber sua opinião sobre a web-aula">
        <template #prepend>
          <v-icon icon="fas fa-file-pen" size="small" class="text-grey-darken-2"></v-icon>
        </template>

        <template #title>
          <span class="fs-20x text-grey-darken-3 font-weight-bold">Questionário de avaliação</span>
        </template>

        <v-card-text class="d-flex flex-column align-center ga-2 px-10">
            <h4 class="text-grey-darken-2 align-self-start">Sobre a web-aula: {{ props.event.name }}</h4>

            <div class="w-100 mt-4">
                <span>Qual seu grau de satisfação com a webaula?</span>

                <v-radio-group class="mt-1" v-model="form.rating_event" density="compact" inline>
                  <div class="d-flex flex-wrap ga-4">
                    <v-radio label="Não informado" color="orange-darken-4" :value="9"></v-radio>
                    <v-radio label="Muito Insatisfeito" color="orange-darken-4" :value="1"></v-radio>
                    <v-radio label="Insatisfeito" color="orange-darken-4" :value="2"></v-radio>
                    <v-radio label="Indiferente" color="orange-darken-4" :value="3"></v-radio>
                    <v-radio label="Satisfeito" color="orange-darken-4" :value="4"></v-radio>
                    <v-radio label="Muito Satisfeito" color="orange-darken-4" :value="5"></v-radio>
                  </div>
                </v-radio-group>
            </div>

            <div class="w-100">
                <span>Qual seu grau de satisfação com o horário da webaula?</span>

                <v-radio-group class="mt-1" v-model="form.rating_event_schedule" density="compact" inline>
                  <div class="d-flex flex-wrap ga-4">
                    <v-radio label="Não informado" color="orange-darken-4" density="small" :value="9"></v-radio>
                    <v-radio label="Muito Insatisfeito" color="orange-darken-4" :value="1"></v-radio>
                    <v-radio label="Insatisfeito" color="orange-darken-4" :value="2"></v-radio>
                    <v-radio label="Indiferente" color="orange-darken-4" :value="3"></v-radio>
                    <v-radio label="Satisfeito" color="orange-darken-4" :value="4"></v-radio>
                    <v-radio label="Muito Satisfeito" color="orange-darken-4" :value="5"></v-radio>
                  </div>
                </v-radio-group>
            </div>

            <div class="d-flex w-100">
              <v-textarea
                label="Sugestões de temas e horários"
                v-model="form.hint"
                density="small"
                required variant="outlined"
                color="orange-darken-4"
                placeholder="Sugestões de temas e horários"
                rows="5"
                autocomplete="false"
                clearable>
              </v-textarea>
            </div>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>

          <v-btn
            :prepend-icon="icon('far fa-paper-plane')"
            color="teal-lighten-1"
            class="font-weight-bold text-none fs-14x"
            variant="tonal"
            size="small"
            @click="emit('print', form)">
            Enviar resposta
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
</template>

<script setup>
import { ref } from 'vue'
import useIcon from '@/composables/useIcon'

import { useCertificateStore } from '@/stores/certificateStore'

const certificateStore = useCertificateStore()

const { icon } = useIcon()

const emit = defineEmits(['print', 'close'])

const props = defineProps({
    event: {
        require: true,
    }
})

const form = ref({
  rating_event: 9,
  rating_event_schedule: 9,
  hint: null
})

</script>