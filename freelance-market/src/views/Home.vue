<template>
  <!-- Hero -->
  <section class="bg-gradient-to-b from-primary to-primary-dark text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 grid md:grid-cols-2 gap-10 items-center">
      <div>
        <h1 class="text-4xl sm:text-5xl font-extrabold leading-tight">Welcome to CorpConnect</h1>
        <p class="mt-4 text-lg text-white/90">Your centralized employee portal for projects, communication, and company updates.</p>
        <div class="mt-8 flex flex-col sm:flex-row gap-3">
          <RouterLink to="/login" class="inline-flex justify-center items-center px-6 py-3 rounded-md bg-accent text-gray-900 font-semibold hover:bg-accent-dark">Sign in</RouterLink>
          <a href="#features" class="inline-flex justify-center items-center px-6 py-3 rounded-md border border-white/20 hover:bg-white/10">Explore Features</a>
        </div>
      </div>
      <div class="relative">
        <div class="aspect-[4/3] rounded-xl bg-white/10 backdrop-blur-md ring-1 ring-white/20 p-6 grid grid-cols-3 gap-4">
          <div v-for="n in 6" :key="n" class="rounded-lg bg-white/20 h-24"></div>
          <div class="col-span-3 rounded-lg bg-white/20 h-24"></div>
        </div>
      </div>
    </div>
  </section>

  <!-- Highlights -->
  <section id="features" class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <article class="rounded-xl border border-gray-200 bg-white shadow-sm p-6">
          <h3 class="font-semibold">Project tracking</h3>
          <p class="text-sm text-gray-600 mt-2">Stay on top of your assignments and deadlines.</p>
        </article>
        <article class="rounded-xl border border-gray-200 bg-white shadow-sm p-6">
          <h3 class="font-semibold">Messaging</h3>
          <p class="text-sm text-gray-600 mt-2">Communicate with your team and leaders in one place.</p>
        </article>
        <article class="rounded-xl border border-gray-200 bg-white shadow-sm p-6">
          <h3 class="font-semibold">Company updates</h3>
          <p class="text-sm text-gray-600 mt-2">Read announcements and reports from leadership.</p>
        </article>
      </div>
    </div>
  </section>

  <!-- Testimonials (internal quotes) -->
  <section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <h2 class="text-2xl font-bold mb-6">What employees say</h2>
      <div class="grid gap-6 md:grid-cols-3">
        <blockquote v-for="t in testimonials" :key="t.id" class="rounded-xl bg-white border border-gray-200 p-6">
          <p class="text-gray-700">“{{ t.quote }}”</p>
          <div class="mt-4 text-sm text-gray-500">— {{ t.author }}</div>
        </blockquote>
      </div>
    </div>
  </section>

  <!-- CTA Footer -->
  <section class="py-12 bg-white border-t border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
      <h3 class="text-xl font-semibold">Ready to get started?</h3>
      <RouterLink to="/login" class="mt-4 inline-flex items-center justify-center px-6 py-3 rounded-md bg-primary text-white hover:bg-primary-dark">Sign in to your account</RouterLink>
    </div>
  </section>
</template>

<script setup>
const testimonials = [
  { id: 1, quote: 'CorpConnect keeps all my work in one place.', author: 'Alex P.' },
  { id: 2, quote: 'Love the clean design and fast navigation.', author: 'Priya S.' },
  { id: 3, quote: 'Announcements and tasks are easy to find.', author: 'Marco T.' },
]
</script>