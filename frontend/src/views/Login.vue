<template>
  <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid md:grid-cols-2 gap-8 items-center">
      <div class="order-2 md:order-1">
        <h1 class="text-2xl font-bold mb-6">Sign in to CorpConnect</h1>

        <form
          @submit.prevent="onSubmit"
          class="space-y-4 bg-white border border-gray-200 rounded-xl p-6"
        >
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input
              v-model="email"
              id="email"
              type="email"
              required
              autocomplete="email"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
            />
          </div>
          <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input
              v-model="password"
              id="password"
              type="password"
              required
              autocomplete="current-password"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
            />
          </div>

          <div class="flex items-center justify-between">
            <label class="inline-flex items-center gap-2 text-sm">
              <input
                type="checkbox"
                v-model="rememberMe"
                class="rounded border-gray-300 text-primary focus:ring-primary"
              />
              Remember me
            </label>
            <RouterLink to="/forgot-password" class="text-sm text-primary hover:underline"
              >Forgot password?</RouterLink
            >
          </div>

          <div v-if="error" class="text-sm text-red-600">{{ error }}</div>

          <button
            type="submit"
            :disabled="loading"
            class="w-full inline-flex items-center justify-center px-4 py-2 rounded-md bg-primary text-white hover:bg-primary-dark disabled:opacity-60"
          >
            <svg
              v-if="loading"
              class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
            >
              <circle
                class="opacity-25"
                cx="12"
                cy="12"
                r="10"
                stroke="currentColor"
                stroke-width="4"
              ></circle>
              <path
                class="opacity-75"
                fill="currentColor"
                d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
              ></path>
            </svg>
            <span>{{ loading ? 'Signing in...' : 'Sign in' }}</span>
          </button>

          <p class="text-sm text-gray-600 text-center">
            Don’t have an account?
            <RouterLink to="/signup" class="text-primary hover:underline">Sign up</RouterLink>
          </p>
        </form>
      </div>

      <div class="order-1 md:order-2">
        <img
          src="https://images.unsplash.com/photo-1529336953121-ad5a0d43d0d3?q=80&w=1200&auto=format&fit=crop"
          alt="CorpConnect illustration"
          class="rounded-xl shadow-md object-cover w-full h-72 md:h-full"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter, RouterLink } from 'vue-router'
import { useUserStore } from '../stores/userStore'

const email = ref('')
const password = ref('')
const rememberMe = ref(false)

const route = useRoute()
const router = useRouter()
const userStore = useUserStore()

const loading = computed(() => userStore.loading)
const error = computed(() => userStore.error)

onMounted(() => {
  if (userStore.token) {
    const role = userStore.user?.role
    router.replace(role === 'admin' ? '/admin' : '/employee')
  }
})

async function onSubmit() {
  const ok = await userStore.login(email.value, password.value, { rememberMe: rememberMe.value })
  if (ok) {
    const role = userStore.user?.role
    const fallback = role === 'admin' ? '/admin' : '/employee'
    const redirect = route.query.redirect || fallback
    router.replace(redirect)
  }
}
</script>
