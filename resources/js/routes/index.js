import { createRouter, createWebHistory }  from 'vue-router'

/* Routes */
import guest from '@/routes/guest'

export default (() => {
  const router = createRouter({
    history: createWebHistory(),
    routes: Array.prototype.concat(guest)
  })

  return router
})()