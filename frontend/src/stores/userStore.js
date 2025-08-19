import { defineStore } from 'pinia'
import api from '../api.js'

export const useUserStore = defineStore('user', {
  state: () => ({
    user: null,
    token: localStorage.getItem('token') || sessionStorage.getItem('token') || null,
    // 'local' if remember me, 'session' otherwise
    persist: localStorage.getItem('token')
      ? 'local'
      : sessionStorage.getItem('token')
        ? 'session'
        : null,
    loading: false,
    error: null,
  }),
  actions: {
    setToken(token, persist = this.persist) {
      this.token = token
      this.persist = token ? persist : null
      if (token) {
        if (persist === 'local') {
          localStorage.setItem('token', token)
          sessionStorage.removeItem('token')
        } else {
          sessionStorage.setItem('token', token)
          localStorage.removeItem('token')
        }
      } else {
        localStorage.removeItem('token')
        sessionStorage.removeItem('token')
      }
    },
    async login(email, password, { rememberMe = false } = {}) {
      this.loading = true
      this.error = null
      try {
        const { data } = await api.post('/auth/login', { email, password })
        const token = data?.token || data?.accessToken
        if (!token) throw new Error('Token not found in response')
        const persist = rememberMe ? 'local' : 'session'
        this.setToken(token, persist)
        await this.fetchUserProfile()
        return true
      } catch (err) {
        this.error = err?.response?.data?.message || err.message || 'Login failed'
        this.setToken(null)
        this.user = null
        return false
      } finally {
        this.loading = false
      }
    },
    async register(name, email, password) {
      this.loading = true
      this.error = null
      try {
        await api.post('/auth/register', { name, email, password })
        return true
      } catch (err) {
        this.error = err?.response?.data?.message || err.message || 'Registration failed'
        return false
      } finally {
        this.loading = false
      }
    },
    async requestPasswordReset(email) {
      this.loading = true
      this.error = null
      try {
        await api.post('/auth/forgot', { email })
        return true
      } catch (err) {
        this.error = err?.response?.data?.message || err.message || 'Request failed'
        return false
      } finally {
        this.loading = false
      }
    },
    async resetPassword(token, newPassword) {
      this.loading = true
      this.error = null
      try {
        await api.post('/auth/reset', { token, password: newPassword })
        return true
      } catch (err) {
        this.error = err?.response?.data?.message || err.message || 'Reset failed'
        return false
      } finally {
        this.loading = false
      }
    },
    logout() {
      this.user = null
      this.setToken(null)
    },
    async fetchUserProfile() {
      if (!this.token) return
      this.loading = true
      this.error = null
      try {
        const { data } = await api.get('/me')
        const role = data?.role || data?.user?.role || 'employee'
        this.user = { ...data, role }
      } catch (err) {
        this.error = err?.response?.data?.message || err.message || 'Failed to load profile'
      } finally {
        this.loading = false
      }
    },
    hasRole(role) {
      return this.user?.role === role
    },
  },
})
