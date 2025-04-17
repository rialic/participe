import Main from '@/pages/private/layout/Main.vue'
import Dashboard from '@/pages/private/Dashboard.vue'
import Event from '@/routes/private/event'
import Smart from '@/routes/private/smart'

export default [
    {
        path: '',
        name: 'main',
        component: Main,
        redirect: () => ({ name: 'home' }),
        meta: { requiresAuth: true },
        children: [
            {
                path: '/dashboard',
                name: 'home',
                component: Dashboard,
                meta: { requiresAuth: true }
            },
            ...Event,
            ...Smart
        ]
    }
]