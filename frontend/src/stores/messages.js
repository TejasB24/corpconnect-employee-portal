import { defineStore } from 'pinia'
import api from '../api.js'

export const useMessagesStore = defineStore('messages', {
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
        const { data } = await api.get('/messages')
        this.items = Array.isArray(data) ? data : data?.messages || []
      } catch (err) {
        this.error = err?.response?.data?.message || err.message || 'Failed to load messages'
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
