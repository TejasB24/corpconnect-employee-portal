/****** @type {import('tailwindcss').Config} *****/
export default {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      colors: {
        primary: { DEFAULT: '#6366F1', dark: '#4F46E5', light: '#A5B4FC' },
        accent: { DEFAULT: '#10B981', dark: '#059669', light: '#6EE7B7' },
      },
    },
  },
  plugins: [],
}