import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/Home.vue'
import Login from '../views/Login.vue'
import Dashboard from '../views/Dashboard.vue'
import { useUserStore } from '../stores/user'

const routes = [
  { path: '/', name: 'Home', component: Home },
  { path: '/login', name: 'Login', component: Login },
  { path: '/dashboard', name: 'Dashboard', component: Dashboard, meta: { requiresAuth: true } },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior() { return { top: 0 } },
})

router.beforeEach((to) => {
  if (to.meta.requiresAuth) {
    const userStore = useUserStore()

    if (!userStore.token) {
      const localToken = localStorage.getItem('token')
      if (localToken) {
        userStore.setToken(localToken)
      } else {
        return { path: '/login', query: { redirect: to.fullPath } }
      }
    }
  }
})

export default router