<template>
    <v-container class="h-100">
        <v-row align="center" justify="center" class="h-100">
            <v-col cols="12" sm="10">
                <v-sheet elevation="1" rounded>
                    <v-card variant="flat">
                        <v-window v-model="step">
                            <v-window-item :value="1">
                                <v-row class="h-550x">
                                    <v-col col="12" md="6">
                                        <v-card-text class="h-100">
                                            <div class="d-flex flex-column justify-end h-100">
                                                <v-row align="center" justify="center">
                                                    <v-col v-if="!showEmailForm" cols="12" sm="8">
                                                        <div class="d-flex flex-column ga-2 mb-4">
                                                            <h3 class="text-center">
                                                                Sistema Participe
                                                            </h3>
                                                        </div>

                                                        <v-form @submit.prevent="logMeIn">
                                                            <div class="d-flex flex-column">
                                                                <v-text-field
                                                                    label="Email"
                                                                    placeholder="Email"
                                                                    v-model="form.email"
                                                                    :error-messages="errorMessage('email', errors)"
                                                                    density="small"
                                                                    clearable
                                                                    variant="outlined"
                                                                    color="orange-darken-4"
                                                                    autocomplete="false">
                                                                </v-text-field>

                                                                <v-text-field
                                                                    label="Senha"
                                                                    placeholder="Senha"
                                                                    v-model="form.password"
                                                                    :error-messages="errorMessage('password', errors)"
                                                                    density="small"
                                                                    variant="outlined"
                                                                    color="orange-darken-4"
                                                                    autocomplete="falase"
                                                                    type="password">
                                                                </v-text-field>
                                                            </div>

                                                            <div class="d-lg-flex justify-space-center align-center mb-6">
                                                                <v-checkbox-btn
                                                                    density="comfortable"
                                                                    v-model="form.remember"
                                                                    class="justify-center justify-lg-start"
                                                                    label="Lembrar me"
                                                                    color="orange-darken-4">
                                                                </v-checkbox-btn>

                                                                <v-btn
                                                                    @click.prevent="showEmailForm = true; errors = []"
                                                                    variant="plain"
                                                                    class="d-flex d-lg-block justify-center pa-0 font-weight-bold text-decoration-underline text-none opacity-90 text-blue-darken-4">
                                                                    Esqueceu a senha?
                                                                </v-btn>
                                                            </div>

                                                            <v-btn :loading="loadingSignInBtn" type="sumit" variant="flat" color="orange-darken-4" class="font-weight-bold mb-8" block>
                                                                Acessar
                                                            </v-btn>
                                                        </v-form>

                                                        <v-divider class="border-opacity-25">Ou continue</v-divider>

                                                        <div class="d-flex justify-center mt-4">
                                                            <v-btn
                                                                variant="outlined"
                                                                @click="showEmailForm = true; errors = []"
                                                                color="gray-lighten-1"
                                                                :prepend-icon="icon('fas fa-wand-sparkles')"
                                                                class="font-weight-bold text-none">
                                                                Login mágico
                                                            </v-btn>
                                                        </div>
                                                    </v-col>

                                                    <v-col v-if="showEmailForm" cols="12" sm="8">
                                                        <div class="d-flex flex-column align-center ga-2 mb-4">
                                                            <h3 class="text-center">
                                                                Sistema Participe
                                                            </h3>

                                                            <span>Informe o seu email de acesso e então enviaremos um link mágico para que você possa acessar o sistema sem usar senha.</span>
                                                        </div>

                                                        <v-form @submit.prevent="logMeInWithEmail">
                                                            <div class="d-flex flex-column">
                                                                <v-text-field
                                                                    label="Email"
                                                                    placeholder="Email"
                                                                    v-model="form.email"
                                                                    :error-messages="errorMessage('email', errors)"
                                                                    density="small"
                                                                    clearable
                                                                    variant="outlined"
                                                                    color="orange-darken-4"
                                                                    autocomplete="false">
                                                                </v-text-field>

                                                                <div class="d-flex justify-center mt-4 ga-2">
                                                                    <v-btn type="button" variant="flat" color="teal-lighten-1" min-width="40" @click.prevent="showEmailForm = false">
                                                                        <v-icon icon="fas fa-arrow-left"></v-icon>
                                                                    </v-btn>

                                                                    <v-btn
                                                                        :loading="loadingSignInMagicBtn"
                                                                        type="submit"
                                                                        variant="flat" color="orange-darken-4" @click.prevent="logMeInWithEmail"
                                                                        :prepend-icon="icon('far fa-paper-plane')" class="font-weight-bold text-none">
                                                                        Enviar email
                                                                    </v-btn>
                                                                </div>
                                                            </div>
                                                        </v-form>
                                                    </v-col>
                                                </v-row>
                                            </div>
                                        </v-card-text>
                                    </v-col>

                                    <v-col cols="12" md="6" class="d-none d-md-block bg-gradient-orange rounded-bs-circle">
                                        <div class="d-flex flex-column justify-center h-100 ga-3">
                                            <div class="d-none flex-column ga-6 bg-white py-6 mx-16 elevation-1 rounded-lg">
                                                <div class="d-flex flex-column ga-2">
                                                    <h3 class="text-center">Não tem uma conta?</h3>

                                                    <h5 class="text-center">
                                                        Clique no botão abaixo para criar uma conta e acessar nossos serviços
                                                    </h5>
                                                </div>

                                                <div class="d-flex justify-center">
                                                    <v-btn variant="flat" color="orange-darken-4" rounded="sm" class="font-weight-bold" @click="step++">
                                                        Cadastra-se
                                                    </v-btn>
                                                </div>
                                            </div>

                                            <div class="d-flex flex-column ga-6 bg-white py-6 mx-16 elevation-1 rounded-lg">
                                                <div class="d-flex flex-column ga-2">
                                                    <h3 class="text-center">Participe das webaulas</h3>

                                                    <h5 class="text-center">
                                                        Clique no botão abaixo para participar das webaulas
                                                    </h5>
                                                </div>

                                                <div class="d-flex justify-center">
                                                    <v-btn variant="flat" color="orange-darken-4" rounded="sm" class="font-weight-bold pulse-orange-darken-4" @click="router.push({ name: 'guest.webclass' })">
                                                        Participe
                                                    </v-btn>
                                                </div>
                                            </div>
                                        </div>
                                    </v-col>
                                </v-row>
                            </v-window-item>

                            <v-window-item :value=2>
                                <v-row>
                                    <v-col cols="2" sm="6" class="bg-gradient-orange rounded-be-circle">
                                        <div class="d-flex flex-column justify-center ga-6 h-100">
                                            <div class="d-flex flex-column ga-6 bg-white py-6 mx-16 elevation-1 rounded-lg">
                                                <div class="d-flex flex-column ga-2">
                                                    <h3 class="text-center">Já possui uma conta?</h3>

                                                    <h5 class="text-center">
                                                        Acesse sua conta para entrar em nosso sistema e utilizar todos os nossos serviços
                                                    </h5>
                                                </div>

                                                <div class="d-flex justify-center ">
                                                    <v-btn variant="flat" color="orange-darken-4" rounded="sm" class="font-weight-bold" @click="step--">
                                                        Entrar
                                                    </v-btn>
                                                </div>
                                            </div>
                                        </div>
                                    </v-col>

                                    <v-col cols="2" sm="6">
                                        <v-card-text style="height: 550px;">
                                            <!-- Here -->
                                        </v-card-text>
                                    </v-col>
                                </v-row>
                            </v-window-item>
                        </v-window>
                    </v-card>
                </v-sheet>
            </v-col>
        </v-row>
    </v-container>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import useAuth from '@/composables/useAuth'
import useIcon from '@/composables/useIcon'
import { errorMessage } from '@/helpers'

import { useAlertStore } from '@/stores/alertStore'

const alertStore = useAlertStore()

const { login, loginWithEmail } = useAuth()
const { icon } = useIcon()
const router = useRouter()
const loadingSignInBtn = ref(false)
const loadingSignInMagicBtn = ref(false)
const step = ref(1)
const errors = ref([])
const showEmailForm = ref(false)
const form = ref({
    email: null,
    password: null,
    remember: false
})

/* functions */
async function logMeIn() {
    loadingSignInBtn.value = true
    const response = await login(form.value)
    loadingSignInBtn.value = false

    if (response.status === 422) {
        alertStore.showAlert = true
        alertStore.setTypeAlert('error')
        alertStore.setMessage(`ReqId: ${response.data.requestId || response.headers['x-request-id']} - Erros foram encontrados.`)

        errors.value = response.data.errors
    }

    if (response.status === 500) {
        alertStore.showAlert = true
        alertStore.setTypeAlert('error')
        alertStore.setMessage(`ReqId: ${response.data.requestId || response.headers['x-request-id']} - Ops... tivemos algum erro em nosso sistema.`)
    }
}

async function logMeInWithEmail() {
    loadingSignInMagicBtn.value = true
    const response = await loginWithEmail(form.value)
    loadingSignInMagicBtn.value = false

    if (response.status === 200) {
        alertStore.showAlert = true
        alertStore.setTypeAlert('success')
        alertStore.setMessage(response.message)
    }

    if (response.status === 422) {
        alertStore.showAlert = true
        alertStore.setTypeAlert('error')
        alertStore.setMessage(`ReqId: ${response.data.requestId || response.headers['x-request-id']} - Erros foram encontrados.`)

        errors.value = response.data.errors
    }

    if (response.status === 500) {
        alertStore.showAlert = true
        alertStore.setTypeAlert('error')
        alertStore.setMessage(`ReqId: ${response.data.requestId || response.headers['x-request-id']} - Ops... tivemos algum erro em nosso sistema.`)
    }
}
</script>

<style scoped>
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