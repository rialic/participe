export default [
    {
        path: '/smart',
        name: 'smart',
        component: () => import('@/pages/private/smart/Smart.vue'),
        meta: { guards: ['MENU.SMART'], requiresAuth: true },
    }
]