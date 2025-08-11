import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/Home.vue'
import Login from '../views/Login.vue'
import EmployeeDashboard from '../views/EmployeeDashboard.vue'
import AdminDashboard from '../views/AdminDashboard.vue'
import { useUserStore } from '../stores/user'

const routes = [
  { path: '/', name: 'Home', component: Home },
  { path: '/login', name: 'Login', component: Login },
  { path: '/employee', name: 'EmployeeDashboard', component: EmployeeDashboard, meta: { requiresAuth: true, roles: ['employee'] } },
  { path: '/admin', name: 'AdminDashboard', component: AdminDashboard, meta: { requiresAuth: true, roles: ['admin'] } },
  // Backwards-compat: redirect /dashboard to role-specific dashboards
  { path: '/dashboard', name: 'DashboardRedirect', beforeEnter: () => {
      const userStore = useUserStore()
      const role = userStore.user?.role
      if (role === 'admin') return { path: '/admin' }
      return { path: '/employee' }
    }
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior() { return { top: 0 } },
})

router.beforeEach(async (to) => {
  const userStore = useUserStore()

  if (to.meta.requiresAuth) {
    // Ensure token exists, otherwise redirect to login
    if (!userStore.token) {
      const localToken = localStorage.getItem('token')
      if (localToken) {
        userStore.setToken(localToken)
      } else {
        return { path: '/login', query: { redirect: to.fullPath } }
      }
    }

    // If route requires role(s), ensure profile is loaded and role matches
    if (to.meta.roles && Array.isArray(to.meta.roles)) {
      if (!userStore.user) {
        try { await userStore.fetchUserProfile() } catch (_) {}
      }
      const role = userStore.user?.role
      if (!role || !to.meta.roles.includes(role)) {
        // Not authorized: send to appropriate dashboard or login
        if (role === 'admin') return { path: '/admin' }
        if (role === 'employee') return { path: '/employee' }
        return { path: '/login' }
      }
    }
  }
})

export default router