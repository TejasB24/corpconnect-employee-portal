import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/Home.vue'
import Login from '../views/Login.vue'
import Signup from '../views/Signup.vue'
import ForgotPassword from '../views/ForgotPassword.vue'
import EmployeeDashboard from '../views/EmployeeDashboard.vue'
import AdminDashboard from '../views/AdminDashboard.vue'
import NotFound from '../views/NotFound.vue'
import { useUserStore } from '../stores/userStore'

const routes = [
  { path: '/', name: 'Home', component: Home },
  { path: '/login', name: 'Login', component: Login },
  { path: '/signup', name: 'Signup', component: Signup },
  { path: '/forgot-password', name: 'ForgotPassword', component: ForgotPassword },
  {
    path: '/employee',
    name: 'EmployeeDashboard',
    component: EmployeeDashboard,
    meta: { requiresAuth: true, roles: ['employee'] },
  },
  {
    path: '/admin',
    name: 'AdminDashboard',
    component: AdminDashboard,
    meta: { requiresAuth: true, roles: ['admin'] },
  },
  {
    path: '/dashboard',
    name: 'DashboardRedirect',
    beforeEnter: () => {
      const userStore = useUserStore()
      const role = userStore.user?.role
      if (role === 'admin') return { path: '/admin' }
      return { path: '/employee' }
    },
  },
  { path: '/:pathMatch(.*)*', name: 'NotFound', component: NotFound },
]

// ... keep the existing guard
const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior() {
    return { top: 0 }
  },
})

router.beforeEach(async (to) => {
  const userStore = useUserStore()

  if (to.meta.requiresAuth) {
    if (!userStore.token) {
      const localToken = localStorage.getItem('token') || sessionStorage.getItem('token')
      if (localToken) {
        userStore.setToken(localToken, localStorage.getItem('token') ? 'local' : 'session')
      } else {
        return { path: '/login', query: { redirect: to.fullPath } }
      }
    }

    if (to.meta.roles && Array.isArray(to.meta.roles)) {
      if (!userStore.user) {
        try {
          await userStore.fetchUserProfile()
        } catch (_) {}
      }
      const role = userStore.user?.role
      if (!role || !to.meta.roles.includes(role)) {
        if (role === 'admin') return { path: '/admin' }
        if (role === 'employee') return { path: '/employee' }
        return { path: '/login' }
      }
    }
  }
})

export default router
