import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

export const useUserStore = defineStore('user', () => {
  const username = ref(null)
  const role = ref(null) // 'employee' | 'admin' | null

  const isLoggedIn = computed(() => !!role.value)

  function setUser({ user, userRole }) {
    username.value = user || null
    role.value = userRole || null
  }

  function logout() {
    username.value = null
    role.value = null
  }

  return { username, role, isLoggedIn, setUser, logout }
})
