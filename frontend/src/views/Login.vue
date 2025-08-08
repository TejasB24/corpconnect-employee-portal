<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md">
      <!-- Title -->
      <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">CorpConnect Portal Login</h1>

      <!-- Role Selector -->
      <div class="mb-4">
        <label class="block text-gray-700 mb-1">Login as</label>
        <select v-model="role" class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-300">
          <option value="employee">Employee</option>
          <option value="admin">Admin</option>
        </select>
      </div>

      <!-- Email -->
      <div class="mb-4">
        <label class="block text-gray-700 mb-1">Email</label>
        <input
          type="email"
          v-model="email"
          placeholder="Enter your email"
          class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-300"
        />
      </div>

      <!-- Password -->
      <div class="mb-4">
        <label class="block text-gray-700 mb-1">Password</label>
        <input
          type="password"
          v-model="password"
          placeholder="Enter your password"
          class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-300"
        />
      </div>

      <!-- Forgot Password -->
      <div class="text-right mb-4">
        <router-link to="/forgot-password" class="text-sm text-blue-600 hover:underline">
          Forgot Password?
        </router-link>
      </div>

      <!-- Login Button -->
      <button
        @click="handleLogin"
        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition"
      >
        Login
      </button>

      <!-- Sign Up -->
      <p class="mt-4 text-center text-sm text-gray-600">
        Don't have an account?
        <router-link to="/signup" class="text-blue-600 hover:underline"> Sign Up </router-link>
      </p>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Login',
  data() {
    return {
      role: 'employee',
      email: '',
      password: '',
    }
  },
  methods: {
    handleLogin() {
      if (!this.email || !this.password) {
        alert('Please enter both email and password')
        return
      }

      // For now just redirect based on role
      if (this.role === 'admin') {
        this.$router.push('/dashboard-admin')
      } else {
        this.$router.push('/dashboard-employee')
      }
    },
  },
}
</script>
