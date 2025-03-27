import { createRouter, createWebHistory }  from 'vue-router'
import useAuth from '@/composables/useAuth'

/* Routes */
import guestRoutes from '@/routes/guest'
import privateRoutes from '@/routes/private'

const { user, authenticated, attemptAuth } = useAuth()

export default (() => {
  const router = createRouter({
    history: createWebHistory(),
    routes: Array.prototype.concat(guestRoutes, privateRoutes)
  })

  router.beforeEach(async (to, from, next) => {
    const authRoutes = ['guest.login']
    const guards = to.meta.guards
    const hasAnyAuthRouteTo = to.matched.some((route) => route.meta.requiresAuth)

    if (hasAnyAuthRouteTo) {
      await attemptAuth()

      if (!authenticated.value) {
        return next({ name: 'guest.login' })
      }

      if(guards?.length > 0 && !guards?.every((guard) => user.value.abilities.includes(guard))) {
        console.log('Here 1')
        return next({ name: 'home' })
      }
    }

    if (authRoutes.includes(to.name)) {
      await attemptAuth()

      if (authenticated.value) {
        return next({ name: 'home' })
      }
    }

    next()
  })

  return router
})()