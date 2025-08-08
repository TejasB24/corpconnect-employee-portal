<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 grid lg:grid-cols-[260px_1fr] gap-6">
    <!-- Sidebar -->
    <aside class="bg-white border border-gray-200 rounded-xl p-5 h-max">
      <h2 class="text-lg font-semibold mb-4">Employee Menu</h2>
      <nav class="space-y-2">
        <a href="#profile" class="block px-3 py-2 rounded-md hover:bg-gray-50">Profile</a>
        <a href="#projects" class="block px-3 py-2 rounded-md hover:bg-gray-50">My Projects</a>
        <a href="#messages" class="block px-3 py-2 rounded-md hover:bg-gray-50">Messages</a>
      </nav>
    </aside>

    <!-- Content -->
    <section class="space-y-6">
      <!-- Profile -->
      <div id="profile" class="bg-white border border-gray-200 rounded-xl p-6">
        <div class="flex items-center justify-between">
          <h2 class="text-lg font-semibold">Your profile</h2>
          <div v-if="loadingProfile" class="text-sm text-gray-500">Loading...</div>
        </div>
        <div v-if="profileError" class="mt-2 text-sm text-red-600">{{ profileError }}</div>
        <div v-else class="mt-4 flex items-center gap-4">
          <div class="h-14 w-14 rounded-full bg-gray-200"></div>
          <div>
            <div class="font-medium">{{ user?.name || '—' }}</div>
            <div class="text-sm text-gray-600">{{ user?.email || '—' }}</div>
            <div class="text-xs text-gray-500">Role: {{ user?.role || '—' }}</div>
          </div>
        </div>
      </div>

      <!-- Projects -->
      <div id="projects" class="bg-white border border-gray-200 rounded-xl p-6">
        <div class="flex items-center justify-between">
          <h2 class="text-lg font-semibold">My active projects</h2>
          <div v-if="loadingProjects" class="text-sm text-gray-500">Loading...</div>
        </div>
        <div v-if="projectsError" class="mt-2 text-sm text-red-600">{{ projectsError }}</div>
        <div v-else class="mt-4 grid gap-4 md:grid-cols-2">
          <div v-for="p in projects" :key="p.id" class="rounded-lg border border-gray-200 p-4">
            <div class="font-medium">{{ p.title }}</div>
            <div class="text-sm text-gray-600">Budget: ${{ p.budget }}</div>
            <div class="mt-2 inline-flex items-center rounded-full bg-accent/10 text-accent px-2.5 py-1 text-xs">{{ p.status }}</div>
          </div>
          <div v-if="projects.length === 0" class="text-gray-600">No active projects.</div>
        </div>
      </div>

      <!-- Messages -->
      <div id="messages" class="bg-white border border-gray-200 rounded-xl p-6">
        <div class="flex items-center justify-between">
          <h2 class="text-lg font-semibold">Messages</h2>
          <div v-if="loadingMessages" class="text-sm text-gray-500">Loading...</div>
        </div>
        <div v-if="messagesError" class="mt-2 text-sm text-red-600">{{ messagesError }}</div>
        <ul v-else class="mt-4 divide-y divide-gray-200">
          <li v-for="m in messages" :key="m.id" class="py-3">
            <div class="flex items-center justify-between">
              <div class="font-medium">{{ m.from }}</div>
              <div class="text-xs text-gray-500">{{ m.time }}</div>
            </div>
            <p class="text-gray-700 text-sm mt-1">{{ m.preview }}</p>
          </li>
          <li v-if="messages.length === 0" class="py-3 text-gray-600">No messages.</li>
        </ul>
      </div>
    </section>
  </div>
</template>

<script setup>
import { onMounted, ref, computed } from 'vue'
import axios from 'axios'
import { useUserStore } from '../stores/user'
import { useRouter } from 'vue-router'

const userStore = useUserStore()
const router = useRouter()

const user = computed(() => userStore.user)
const loadingProfile = ref(false)
const profileError = ref('')

const projects = ref([])
const loadingProjects = ref(false)
const projectsError = ref('')

const messages = ref([])
const loadingMessages = ref(false)
const messagesError = ref('')

onMounted(async () => {
  if (!userStore.token) {
    router.replace('/login')
    return
  }

  if (!userStore.user) {
    loadingProfile.value = true
    await userStore.fetchUserProfile().catch(() => {})
    loadingProfile.value = false
    profileError.value = userStore.error || ''
  }

  await fetchProjects()
  await fetchMessages()
})

async function fetchProjects() {
  loadingProjects.value = true
  projectsError.value = ''
  try {
    const url = `${import.meta.env.VITE_API_URL}/projects`
    const { data } = await axios.get(url, {
      headers: { Authorization: `Bearer ${userStore.token}` },
    })
    projects.value = Array.isArray(data) ? data : (data?.projects || [])
  } catch (err) {
    projectsError.value = err?.response?.data?.message || err.message || 'Failed to load projects'
    projects.value = []
  } finally {
    loadingProjects.value = false
  }
}

async function fetchMessages() {
  loadingMessages.value = true
  messagesError.value = ''
  try {
    const url = `${import.meta.env.VITE_API_URL}/messages`
    const { data } = await axios.get(url, {
      headers: { Authorization: `Bearer ${userStore.token}` },
    })
    messages.value = Array.isArray(data) ? data : (data?.messages || [])
  } catch (err) {
    messagesError.value = err?.response?.data?.message || err.message || 'Failed to load messages'
    messages.value = []
  } finally {
    loadingMessages.value = false
  }
}
</script>