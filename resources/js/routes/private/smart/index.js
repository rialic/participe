export default [
    {
        path: 'smart',
        name: 'smart',
        component: () => import('@/pages/private/smart/Smart.vue'),
        meta: { guards: ['MENU.SMART'], requiresAuth: true },
    },
    {
        path: 'smart/estabelecimentos',
        name: 'smart.establishment',
        component: () => import('@/pages/private/smart/Establishment.vue'),
        meta: { guards: ['SMART.ESTABLISHMENT'], requiresAuth: true },
    },
    {
        path: 'smart/profissionais',
        name: 'smart.professionals',
        component: () => import('@/pages/private/smart/Professionals.vue'),
        meta: { guards: ['SMART.ESTABLISHMENT'], requiresAuth: true },
    },
    {
        path: 'smart/tele-educacao',
        name: 'smart.webs',
        component: () => import('@/pages/private/smart/Webs.vue'),
        meta: { guards: ['SMART.WEBCLASS'], requiresAuth: true },
    },
    {
        path: 'smart/teleconsultoria',
        name: 'smart.teleconsulting',
        component: () => import('@/pages/private/smart/Webconsulting.vue'),
        meta: { guards: ['SMART.WEBCONSULTING'], requiresAuth: true },
    }
]