import { defineStore } from 'pinia'
import api from '../api.js'

export const useProjectsStore = defineStore('projects', {
  state: () => ({
    items: [],
    loading: false,
    error: null,
  }),
  actions: {
    async fetch() {
      this.loading = true
      this.error = null
      try {
        const { data } = await api.get('/projects')
        this.items = Array.isArray(data) ? data : data?.projects || []
      } catch (err) {
        this.error = err?.response?.data?.message || err.message || 'Failed to load projects'
        this.items = []
      } finally {
        this.loading = false
      }
    },
    clear() {
      this.items = []
      this.error = null
      this.loading = false
    },
  },
})
