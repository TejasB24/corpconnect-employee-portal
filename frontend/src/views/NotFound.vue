<template>
  <div class="min-h-[60vh] flex items-center justify-center px-4">
    <div class="text-center">
      <h1 class="text-5xl font-extrabold text-primary">404</h1>
      <p class="mt-3 text-gray-700">Page not found</p>
      <RouterLink
        to="/"
        class="mt-6 inline-flex items-center justify-center px-6 py-3 rounded-md bg-primary text-white hover:bg-primary-dark"
        >Go home</RouterLink
      >
    </div>
  </div>
</template>

<script setup>
import { RouterLink } from 'vue-router'
</script>
