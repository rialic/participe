import axios from 'axios'
import { reactive, computed} from 'vue'
import { useUserStore } from '@/stores/userStore'
import { useRouter } from 'vue-router'

const state = reactive({ user: null, authenticated: false })

export default function useAuth() {
    const router = useRouter()
    const getUser = computed(() => state.user)
    const getAuthenticated = computed(() => state.authenticated)
    axios.defaults.baseURL = import.meta.env.VITE_URL
    axios.defaults.withCredentials = true
    axios.defaults.headers.common['Content-Type'] = 'application/json'
    axios.defaults.headers.common['Accept'] = 'application/json'
    axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

    async function attemptAuth() {
      if (!state.user) {
        const { me } = useUserStore()
        const response = await me()
        const user = response.data

        if (response.status === 200) {
            authenticate(user)
        }

        if (response.status === 401) {
            authenticate(null, false)
        }
      }
    }

    function authenticate(user, authenticated = true) {
      state.user = user
      state.authenticated = authenticated
    }

    async function login(data) {
        await axios({ method: 'get', url: `/sanctum/csrf-cookie` })

        try {
          const response = await axios({ method: 'post', url: `/api/login`, data: data })

          if (response.status === 200) {
            const user = response.data

            authenticate(user)
            router.replace({ name: 'home' })
          }

          return response
        } catch (errors) {
          return { status: errors.response.status, headers: errors.response.headers, data: errors.response.data }
        }
      }

      async function loginWithEmail(data) {
        try {
          const response = await axios({ method: 'post', url: `/api/v1/magic-link`, data: data })

          if (response.status === 200) {
            return response.data
          }
        } catch (errors) {
          return { status: errors.response.status, headers: errors.response.headers, data: errors.response.data }
        }
      }

      async function logout() {
        try {
          const response = await axios({ method: 'post', url: `/api/logout` })

          if (response.status === 200 || response.status === 204) {
            authenticate(null, false)
            router.push({ name: 'guest.login' })
          }
        } catch (errors) {
          return { status: errors.response.status, headers: errors.response.headers, data: errors.response.data }
        }
      }

    return {
      attemptAuth,
      login,
      loginWithEmail,
      logout,
      user: getUser,
      authenticated: getAuthenticated
    }
}