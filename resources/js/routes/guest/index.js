import Index from '@/pages/Index.vue'

export default [
    {
        path: '/',
        name: 'guest.login',
        component: Index
    },
    {
        path: '/webaulas',
        name: 'guest.webclass',
        component: () => import('@/pages/guest/WebClass.vue'),
    },
    {
        path: '/certificados',
        name: 'guest.certificates',
        component: () => import('@/pages/guest/Certificates.vue'),
    }
]