import { createRouter, createWebHistory }  from 'vue-router'
import useAuth from '@/composables/useAuth'

/* Routes */
import guestRoutes from '@/routes/guest'
import privateRoutes from '@/routes/private'

const { user, authenticated, attemptAuth } = useAuth()

export default (() => {
  const router = createRouter({
    history: createWebHistory(),
    routes: Array.prototype.concat(
      guestRoutes,
      privateRoutes,
      [
        {
          path: '/:pathMatch(.*)*', name: 'not-found', component: () => import('@/pages/NotFound.vue')
        }
      ]
    ),
    scrollBehavior(to, from, savedPosition) {
      return savedPosition || new Promise((resolve) => {
        return setTimeout(() => resolve({ top: 0, behavior: 'smooth' }), 300)
      })
    }
  })

  router.beforeEach(async (to, from, next) => {
    const authRoutes = ['guest.login']
    const guards = to.meta.guards
    const hasAnyAuthRouteTo = to.matched.some((route) => route.meta.requiresAuth)

    if (hasAnyAuthRouteTo) {
      await attemptAuth()

      const routeToHasGuards = guards?.length > 0
      const userHasGuards = guards?.every((guard) => user.value?.abilities.includes(guard))

      if (!authenticated.value) {
        return next({ name: 'guest.login' })
      }

      if(routeToHasGuards && !userHasGuards) {
        const isDefaultUser = !!user.value.abilities.find((ability) => ability === 'HOME')

        return next({ name: isDefaultUser ? 'home' : 'dashboard' })
      }
    }

    if (authRoutes.includes(to.name)) {
      if(!user.value) await attemptAuth()

      if (authenticated.value) {
        const isDefaultUser = !!user.value.abilities.find((ability) => ability === 'HOME')

        return next({ name: isDefaultUser ? 'home' : 'dashboard' })
      }
    }

    next()
  })

  return router
})()