import { computed } from 'vue'
import axios from 'axios'
import { useAlertStore } from '@/stores/alertStore'
import { useAppStore } from '@/stores/appStore'

export default (() => {
  let instance
  const alertStore = computed(() => useAlertStore())
  const appStore = computed(() => useAppStore())
  const domain = import.meta.env.VITE_URL
  const baseUrl = `${domain}/api`

  function Axios() {
    if (instance) {
      return instance
    }

    instance = axios.create({
      baseURL: baseUrl,
      withCredentials: true,
      headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json, application/octet-stream',
        'X-Requested-With': 'XMLHttpRequest',
      },
    })

    instance.interceptors.request.use(function(config) {
    appStore.value.setOverlay(true)

      return config
    }, null)

    instance.interceptors.response.use(function(response) {
      const isSuccessStatus = response.status === 201 || response.status === 200
      const isMethodDiffGet = response.config.method !== 'get'
      const isSuccessStatusWithMessage = response.data?.message
      const isDataBlob = Object.prototype.toString.call(response.data) === '[object Blob]'
      const data = (Object.values(response.data).length || isDataBlob) ? response.data.data || response.data : null

      if (isMethodDiffGet && isSuccessStatus && isSuccessStatusWithMessage) {
        alertStore.value.showAlert = true
        alertStore.value.setTypeAlert('success')
        alertStore.value.setMessage(response.data.message)
      }

      appStore.value.setOverlay(false)

      return {
        ok: true,
        status: response.status,
        data: data,
        links: response.data?.links,
        meta: response.data?.meta,
        message: data?.message
      }
    },
    function({ response, message }) {
      if (response.status === 422 || response.status === 400) {
        alertStore.value.showAlert = true

        alertStore.value.setTypeAlert('error')
        alertStore.value.setMessage(`ReqId: ${response.data.requestId || response.headers['x-request-id']} - Erros foram encontrados.`)
      }

      if (response.status === 500) {
        alertStore.value.showAlert = true

        alertStore.value.setTypeAlert('error')
        alertStore.value.setMessage(`ReqId: ${response.data.requestId || response.headers['x-request-id']} - Ops... tivemos algum erro em nosso sistema. ${response.data.errors?.message || ''}`)
        appStore.value.setOverlay(false)

        return
      }

      appStore.value.setOverlay(false)

      return {
        ok: false,
        data: {
          errors: response.data?.errors
        },
        status: response.status,
        message: response.data?.message
      }
    })

    return instance
  }

  return instance || new Axios
})()