import Index from '@/pages/Index.vue'
import WebClass from '@/pages/guest/WebClass.vue'

export default [
    {
        path: '',
        name: 'guest.login',
        component: Index
    },
    {
        path: '/webaulas',
        name: 'guest.webclass',
        component: WebClass,
    },
    {
        path: '/certificados',
        name: 'guest.certificates',
        component: () => import('@/pages/guest/Certificates.vue'),
    }
]