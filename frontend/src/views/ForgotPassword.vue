<template>
  <div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <h1 class="text-2xl font-bold mb-6">Forgot your password?</h1>

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

      <div v-if="error" class="text-sm text-red-600">{{ error }}</div>
      <div v-if="success" class="text-sm text-green-600">
        If an account exists for this email, a reset link has been sent.
      </div>

      <button
        type="submit"
        :disabled="loading"
        class="w-full inline-flex items-center justify-center px-4 py-2 rounded-md bg-primary text-white hover:bg-primary-dark disabled:opacity-60"
      >
        <span>{{ loading ? 'Sending...' : 'Send reset link' }}</span>
      </button>

      <p class="text-sm text-gray-600 text-center">
        <RouterLink to="/login" class="text-primary hover:underline">Back to sign in</RouterLink>
      </p>
    </form>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { RouterLink } from 'vue-router'
import { useUserStore } from '../stores/userStore'

const email = ref('')
const success = ref(false)
const userStore = useUserStore()
const loading = computed(() => userStore.loading)
const error = computed(() => userStore.error)

async function onSubmit() {
  const ok = await userStore.requestPasswordReset(email.value)
  if (ok) success.value = true
}
</script>
