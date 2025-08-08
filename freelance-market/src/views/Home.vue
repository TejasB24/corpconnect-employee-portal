<template>
  <!-- Hero -->
  <section class="bg-gradient-to-b from-primary to-primary-dark text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 grid md:grid-cols-2 gap-10 items-center">
      <div>
        <h1 class="text-4xl sm:text-5xl font-extrabold leading-tight">Hire top talent for your next big idea</h1>
        <p class="mt-4 text-lg text-white/90">Discover vetted freelancers across design, development, marketing, and more.</p>
        <div class="mt-8 flex flex-col sm:flex-row gap-3">
          <RouterLink to="/login" class="inline-flex justify-center items-center px-6 py-3 rounded-md bg-accent text-gray-900 font-semibold hover:bg-accent-dark">Get Started</RouterLink>
          <a href="#categories" class="inline-flex justify-center items-center px-6 py-3 rounded-md border border-white/20 hover:bg-white/10">Browse Categories</a>
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

  <!-- Featured Freelancers -->
  <section class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-end justify-between mb-6">
        <h2 class="text-2xl font-bold">Featured freelancers</h2>
        <a href="#" class="text-primary hover:underline">View all</a>
      </div>
      <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <article v-for="f in freelancers" :key="f.id" class="rounded-xl border border-gray-200 bg-white shadow-sm p-6 flex items-center gap-4">
          <img :src="f.avatar" :alt="f.name" class="h-14 w-14 rounded-full object-cover" />
          <div class="flex-1">
            <h3 class="font-semibold">{{ f.name }}</h3>
            <p class="text-sm text-gray-600">{{ f.title }}</p>
            <div class="mt-2 text-sm text-gray-500">Rate: <span class="font-medium text-gray-700">${{ f.rate }}/hr</span></div>
          </div>
          <button class="px-3 py-2 rounded-md bg-primary text-white text-sm hover:bg-primary-dark">Hire</button>
        </article>
      </div>
    </div>
  </section>

  <!-- Categories -->
  <section id="categories" class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <h2 class="text-2xl font-bold mb-6">Popular categories</h2>
      <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
        <div v-for="c in categories" :key="c" class="rounded-lg bg-white border border-gray-200 p-5 hover:shadow-sm">{{ c }}</div>
      </div>
    </div>
  </section>

  <!-- Testimonials -->
  <section class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <h2 class="text-2xl font-bold mb-6">What clients say</h2>
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
      <h3 class="text-xl font-semibold">Ready to find your next expert?</h3>
      <RouterLink to="/login" class="mt-4 inline-flex items-center justify-center px-6 py-3 rounded-md bg-primary text-white hover:bg-primary-dark">Create an account</RouterLink>
    </div>
  </section>
</template>

<script setup>
const freelancers = [
  { id: 1, name: 'Ava Thompson', title: 'Senior UI/UX Designer', rate: 65, avatar: 'https://i.pravatar.cc/100?img=1' },
  { id: 2, name: 'Liam Chen', title: 'Full‑Stack Developer', rate: 80, avatar: 'https://i.pravatar.cc/100?img=2' },
  { id: 3, name: 'Sofia Martinez', title: 'Content Strategist', rate: 55, avatar: 'https://i.pravatar.cc/100?img=3' },
]

const categories = [
  'Web Development', 'Mobile Apps', 'UI/UX Design', 'Content Writing', 'SEO', 'Data Science', 'Marketing', 'Video Editing'
]

const testimonials = [
  { id: 1, quote: 'Found an amazing developer in hours, not weeks.', author: 'Grace K.' },
  { id: 2, quote: 'The process is smooth and the talent is top‑notch.', author: 'Mohamed A.' },
  { id: 3, quote: 'We scale projects quickly thanks to FreelanceHub.', author: 'Emily R.' },
]
</script>