<template>
  <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid md:grid-cols-2 gap-8 items-center">
      <div class="order-2 md:order-1">
        <h1 class="text-2xl font-bold mb-6">Create your CorpConnect account</h1>

        <form
          @submit.prevent="onSubmit"
          class="space-y-4 bg-white border border-gray-200 rounded-xl p-6"
        >
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Full name</label>
            <input
              v-model="name"
              id="name"
              type="text"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
            />
          </div>
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
              autocomplete="new-password"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
            />
          </div>

          <div v-if="error" class="text-sm text-red-600">{{ error }}</div>
          <div v-if="success" class="text-sm text-green-600">
            Registration successful. You can now sign in.
          </div>

          <button
            type="submit"
            :disabled="loading"
            class="w-full inline-flex items-center justify-center px-4 py-2 rounded-md bg-primary text-white hover:bg-primary-dark disabled:opacity-60"
          >
            <span>{{ loading ? 'Creating account...' : 'Sign up' }}</span>
          </button>

          <p class="text-sm text-gray-600 text-center">
            Already have an account?
            <RouterLink to="/login" class="text-primary hover:underline">Sign in</RouterLink>
          </p>
        </form>
      </div>

      <div class="order-1 md:order-2">
        <img
          src="https://images.unsplash.com/photo-1551836022-d5d88e9218df?q=80&w=1200&auto=format&fit=crop"
          alt="Sign up illustration"
          class="rounded-xl shadow-md object-cover w-full h-72 md:h-full"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { RouterLink, useRouter } from 'vue-router'
import { useUserStore } from '../stores/userStore'

const name = ref('')
const email = ref('')
const password = ref('')
const success = ref(false)

const router = useRouter()
const userStore = useUserStore()
const loading = computed(() => userStore.loading)
const error = computed(() => userStore.error)

async function onSubmit() {
  const ok = await userStore.register(name.value, email.value, password.value)
  if (ok) {
    success.value = true
    setTimeout(() => router.replace('/login'), 800)
  }
}
</script>
