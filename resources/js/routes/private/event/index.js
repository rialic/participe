export default [
    {
        path: 'webs',
        name: 'webs',
        component: () => import('@/pages/private/event/WebsMainPage.vue'),
        meta: { guards: ['EVENT.WEBCLASS-VIEW'], requiresAuth: true },
        children: [
            {
                path: '',
                name: 'webs.view',
                component: () => import('@/pages/private/event/WebsListPage.vue'),
                meta: { guards: ['EVENT.WEBCLASS-VIEW'], requiresAuth: true },
            },
            {
                path: 'novo',
                name: 'webs.new',
                component: () => import('@/pages/private/event/WebsFormPage.vue'),
                meta: { guards: ['EVENT.WEBCLASS-CREATE'], requiresAuth: true },
            }
        ]
    }
]