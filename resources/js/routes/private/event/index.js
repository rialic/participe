import WebsPageMain from '@/pages/private/event/WebsPageMain.vue'
import WebsPageList from '@/pages/private/event/WebsPageList.vue'
import WebsPageForm from '@/pages/private/event/WebsPageForm.vue'

export default [
    {
        path: 'webs',
        name: 'webs',
        component: WebsPageMain,
        meta: { guards: ['EVENT.WEBCLASS-VIEW'], requiresAuth: true },
        children: [
            {
                path: '',
                name: 'webs.view',
                component: WebsPageList,
                meta: { guards: ['EVENT.WEBCLASS-VIEW'], requiresAuth: true },
            },
            {
                path: 'criar',
                name: 'webs.new',
                component: WebsPageForm,
                meta: { guards: ['EVENT.WEBCLASS-CREATE'], requiresAuth: true },
            },
            {
                path: ':uuid/editar',
                name: 'webs.edit',
                component: WebsPageForm,
                props: (route) => ({ uuid: route.params.uuid }),
                meta: { guards: ['EVENT.WEBCLASS-CREATE'], requiresAuth: true },
            },
            {
                path: 'relatorios',
                name: 'webs.report',
                component: () => import('@/pages/private/event/WebsReport.vue'),
                meta: { guards: ['EVENT.WEBCLASS-REPORT'], requiresAuth: true },
            },
        ]
    }
]