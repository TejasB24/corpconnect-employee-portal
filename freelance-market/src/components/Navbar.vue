<template>
  <nav class="bg-white border-b border-gray-200 sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex h-16 items-center justify-between">
        <div class="flex items-center">
          <RouterLink to="/" class="text-xl font-bold text-primary">Freelance<span class="text-accent">Hub</span></RouterLink>
        </div>
        <div class="hidden md:flex items-center space-x-6">
          <RouterLink to="/" class="hover:text-primary">Home</RouterLink>
          <RouterLink v-if="!isLoggedIn" to="/login" class="px-4 py-2 rounded-md bg-primary text-white hover:bg-primary-dark">Login</RouterLink>
          <div v-else class="flex items-center space-x-4">
            <RouterLink to="/dashboard" class="hover:text-primary">Dashboard</RouterLink>
            <button @click="handleLogout" class="px-3 py-2 rounded-md border border-gray-300 hover:bg-gray-50">Logout</button>
          </div>
        </div>
        <button class="md:hidden inline-flex items-center justify-center rounded-md p-2 text-gray-700 hover:bg-gray-100" @click="mobileOpen = !mobileOpen" aria-label="Open main menu">
          <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path v-if="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>
    </div>
    <div v-if="mobileOpen" class="md:hidden border-t border-gray-200">
      <div class="px-4 py-3 space-y-2">
        <RouterLink @click="mobileOpen=false" to="/" class="block py-2">Home</RouterLink>
        <RouterLink v-if="!isLoggedIn" @click="mobileOpen=false" to="/login" class="block py-2">Login</RouterLink>
        <div v-else class="space-y-2">
          <RouterLink @click="mobileOpen=false" to="/dashboard" class="block py-2">Dashboard</RouterLink>
          <button @click="handleLogout" class="w-full text-left py-2">Logout</button>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref, computed } from 'vue'
import { RouterLink, useRouter } from 'vue-router'
import { useUserStore } from '../stores/user'

const mobileOpen = ref(false)
const router = useRouter()
const userStore = useUserStore()
const isLoggedIn = computed(() => !!userStore.token)

function handleLogout() {
  userStore.logout()
  router.push('/login')
  mobileOpen.value = false
}
</script>