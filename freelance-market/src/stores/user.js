import { defineStore } from 'pinia'
import axios from 'axios'

export const useUserStore = defineStore('user', {
  state: () => ({
    user: null,
    token: localStorage.getItem('token') || null,
    loading: false,
    error: null,
  }),
  actions: {
    setToken(token) {
      this.token = token
      if (token) {
        localStorage.setItem('token', token)
      } else {
        localStorage.removeItem('token')
      }
    },
    async login(email, password) {
      this.loading = true
      this.error = null
      try {
        const url = `${import.meta.env.VITE_API_URL}/auth/login`
        const { data } = await axios.post(url, { email, password })
        const token = data?.token || data?.accessToken
        if (!token) throw new Error('Token not found in response')
        this.setToken(token)
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
    logout() {
      this.user = null
      this.setToken(null)
    },
    async fetchUserProfile() {
      if (!this.token) return
      this.loading = true
      this.error = null
      try {
        const url = `${import.meta.env.VITE_API_URL}/me`
        const { data } = await axios.get(url, {
          headers: { Authorization: `Bearer ${this.token}` },
        })
        // Ensure role exists; default to 'employee' if backend does not provide
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