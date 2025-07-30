import Main from '@/pages/private/layout/Main.vue'
import Home from '@/pages/private/Home.vue'
import Dashboard from '@/pages/private/Dashboard.vue'
import Event from '@/routes/private/event'
import Smart from '@/routes/private/smart'

export default [
    {
        path: '/',
        name: 'main',
        component: Main,
        // redirect: () => ({ name: 'dashboard' }),
        meta: { requiresAuth: true },
        children: [
            {
                path: '/dashboard',
                name: 'dashboard',
                component: Dashboard,
                meta: { requiresAuth: true }
            },
            {
                path: '/home',
                name: 'home',
                component: Home,
                meta: { requiresAuth: true }
            },
            ...Event,
            ...Smart
        ]
    }
]