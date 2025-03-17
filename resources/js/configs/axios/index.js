import axios from 'axios'
import { useAlertStore } from '@/stores/AlertStore'
import { useAppStore } from '@/stores/AppStore'

export default (() => {
  let instance
  const alertStore = useAlertStore()
  const appStore = useAppStore()
  const domain = import.meta.env.VITE_URL
  const baseUrl = `https://${domain}/api`

  function Axios() {
    if (instance) {
      return instance
    }

    instance = axios.create({
      baseURL: baseUrl,
      withCredentials: true,
      headers: {
        Accept: 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
      },
    })

    instance.interceptors.request.use(function(config) {
    appStore.setOverlay(true)

      return config
    }, null)

    instance.interceptors.response.use(function(response) {
      const isSuccessStatus = response.status === 201 || response.status === 200
      const isMethodDiffGet = response.config.method !== 'get'
      const isSuccessStatusWithMessage = response.data?.message
      const isDataBlob = Object.prototype.toString.call(response.data) === '[object Blob]'
      const data = (Object.values(response.data).length || isDataBlob) ? response.data.data || response.data : null

      if (isMethodDiffGet && isSuccessStatus && isSuccessStatusWithMessage) {
        alertStore.showAlert = true
        alertStore.setTypeAlert('success')
        alertStore.setMessage(response.data.message)
      }

      appStore.setOverlay(false)

      return {
        ok: true,
        status: response.status,
        data: data,
        links: data?.links,
        meta: data?.meta,
        message: data?.message
      }
    },
    function({ response, message }) {
      if (response.status === 422 || response.status === 400) {
        alertStore.showAlert = true
        alertStore.setTypeAlert('error')
        alertStore.setMessage(`ReqId: ${response.data.requestId || response.headers['x-request-id']} - Erros foram encontrados.`)
      }

      if (response.status === 500) {
        alertStore.showAlert = true
        alertStore.setTypeAlert('error')
        alertStore.setMessage(`ReqId: ${response.data.requestId || response.headers['x-request-id']} - Ops... tivemos algum erro em nosso sistema.`)

        appStore.setOverlay(false)

        return
      }

      appStore.setOverlay(false)

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