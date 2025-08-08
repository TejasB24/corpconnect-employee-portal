import { createRouter, createWebHistory } from 'vue-router'
import { useUserStore } from '../stores/userStore'

import GuestPage from '../views/GuestPage.vue'
import Login from '../views/Login.vue'
import DashboardEmployee from '../views/DashboardEmployee.vue'
import DashboardAdmin from '../views/DashboardAdmin.vue'
import NotFound from '../views/NotFound.vue'

const routes = [
  { path: '/', name: 'Guest', component: GuestPage },
  { path: '/login', name: 'Login', component: Login },
  {
    path: '/employee',
    name: 'EmployeeDashboard',
    component: DashboardEmployee,
    meta: { requiresAuth: true, role: 'employee' },
  },
  {
    path: '/admin',
    name: 'AdminDashboard',
    component: DashboardAdmin,
    meta: { requiresAuth: true, role: 'admin' },
  },
  { path: '/:pathMatch(.*)*', name: 'NotFound', component: NotFound },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

// Navigation guard: checks authentication and role
router.beforeEach((to, from, next) => {
  const store = useUserStore()

  // public routes
  if (!to.meta.requiresAuth) {
    return next()
  }

  // requires auth
  if (!store.role) {
    // not logged in -> send to login, preserve attempted route
    return next({ name: 'Login', query: { redirect: to.fullPath } })
  }

  // role protected
  if (to.meta.role && store.role !== to.meta.role) {
    // trying to access other role's page -> redirect to user's dashboard
    return next({ name: store.role === 'admin' ? 'AdminDashboard' : 'EmployeeDashboard' })
  }

  return next()
})

export default router
