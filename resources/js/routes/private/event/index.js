export default [
    {
        path: 'webs',
        name: 'webs',
        component: () => import('@/pages/private/event/WebsPageMain.vue'),
        meta: { guards: ['EVENT.WEBCLASS-VIEW'], requiresAuth: true },
        children: [
            {
                path: '',
                name: 'webs.view',
                component: () => import('@/pages/private/event/WebsPageList.vue'),
                meta: { guards: ['EVENT.WEBCLASS-VIEW'], requiresAuth: true },
            },
            {
                path: 'criar',
                name: 'webs.new',
                component: () => import('@/pages/private/event/WebsPageForm.vue'),
                meta: { guards: ['EVENT.WEBCLASS-CREATE'], requiresAuth: true },
            },
            {
                path: ':uuid/editar',
                name: 'webs.edit',
                component: () => import('@/pages/private/event/WebsPageForm.vue'),
                props: (route) => ({ uuid: route.params.uuid }),
                meta: { guards: ['EVENT.WEBCLASS-CREATE'], requiresAuth: true },
            }
        ]
    }
]