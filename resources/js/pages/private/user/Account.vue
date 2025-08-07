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

    <v-form  @submit.prevent="save">
        <v-sheet class="pa-0 pa-md-4">
            <v-row>
                <v-col cols="12" md="6" class="py-1">
                    <v-text-field
                        label="Nome *"
                        v-model="form.name"
                        disabled
                        :error-messages="errorMessage('name', errors)"
                        density="small"
                        required variant="outlined"
                        color="orange-darken-4"
                        placeholder="Nome"
                        autocomplete="false"
                        clearable>
                    </v-text-field>
                </v-col>

                <v-col cols="12" md="6" class="py-1">
                    <v-text-field
                        label="Email *"
                        v-model="form.email"
                        :error-messages="errorMessage('email', errors)"
                        density="small"
                        required variant="outlined"
                        color="orange-darken-4"
                        placeholder="Email"
                        autocomplete="false"
                        clearable>
                    </v-text-field>
                </v-col>
            </v-row>

            <v-row>
                <v-col cols="12" md="6" class="py-1">
                    <v-text-field
                        label="Senha *"
                        v-model="form.password"
                        :error-messages="errorMessage('password', errors)"
                        density="small"
                        required variant="outlined"
                        color="orange-darken-4"
                        placeholder="Senha"
                        autocomplete="false"
                        type="password"
                        clearable>
                    </v-text-field>
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
                size="small">
                Salvar
            </v-btn>
        </div>
    </v-form>

</template>

<script setup>
import { onMounted, ref } from 'vue'
import { errorMessage, presetFilter } from '@/helpers'
import { useRouter } from 'vue-router'
import useIcon from '@/composables/useIcon'

import { useAppStore } from '@/stores/appStore'
import { useEventStore } from '@/stores/eventStore'
import { useUserStore } from '@/stores/userStore'
import useAuth from '@/composables/useAuth'

const appStore = useAppStore()
const eventStore = useEventStore()
const userStore = useUserStore()
const { user } = useAuth()

const router = useRouter()
const { icon } = useIcon()
const errors = ref([])
const form = ref({
    uuid: null,
    name: null,
    email: null,
    password: null,
})

/* onMounted */
onMounted(async () => {
    appStore.pageTitle = 'Perfil'
    eventStore.title = 'Perfil'

    form.value.uuid = user.value.uuid
    form.value.name = user.value.name
    form.value.email = user.value.email
})

/* Functions */
async function save() {
    const response = await userStore.update(presetFilter(form.value), form.value.uuid)

    if (response.status === 200 || response.status === 201) {
        if (form.value.password) {
            router.push({ name: 'dashboard' })
        }
    }

    if (response.status === 422) {
        errors.value = response.data.errors
    }
}
</script>