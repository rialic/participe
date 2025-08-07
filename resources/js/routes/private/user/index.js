export default [
    {
        path: 'minha-conta',
        name: 'user.account',
        component: () => import('@/pages/private/user/Account.vue'),
        meta: { requiresAuth: true },
    }
]