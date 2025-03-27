import Event from '@/routes/private/event'
import Smart from '@/routes/private/smart'

export default [
    {
        path: '/main',
        name: 'main',
        component: () => import('@/pages/private/layout/Main.vue'),
        meta: { requiresAuth: true },
        children: [
            {
                path: '/home',
                name: 'home',
                component: () => import('@/pages/private/Dashboard.vue'),
                meta: { requiresAuth: true }
            },
            ...Event,
            ...Smart
        ]
    }
]