export default [
    {
        path: '/eventos',
        name: 'event',
        component: () => import('@/pages/private/event/Event.vue'),
        meta: { guards: ['MENU.EVENT'], requiresAuth: true },
    }
]