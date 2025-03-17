export default [
    {
        path: '/',
        name: 'guest.home',
        component: () => import('@/pages/Index.vue')
    },
    {
        path: '/web-aulas',
        name: 'guest.webclass',
        component: () => import('@/pages/guest/WebClass.vue')
    },
    {
        path: '/certificados',
        name: 'guest.certificates',
        component: () => import('@/pages/guest/Certificates.vue')
    }
]