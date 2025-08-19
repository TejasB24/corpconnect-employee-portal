import axios from 'axios'
import router from './router/index.js'

const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL,
})

const getToken = () => localStorage.getItem('token') || sessionStorage.getItem('token')

api.interceptors.request.use((config) => {
  const token = getToken()
  if (token) {
    config.headers = config.headers || {}
    config.headers.Authorization = `Bearer ${token}`
  }
  return config
})

api.interceptors.response.use(
  (res) => res,
  (error) => {
    const status = error?.response?.status
    if (status === 401) {
      const currentPath = router.currentRoute.value.fullPath
      localStorage.removeItem('token')
      sessionStorage.removeItem('token')
      if (currentPath !== '/login') {
        router.replace({ path: '/login', query: { redirect: currentPath } })
      }
    }
    return Promise.reject(error)
  },
)

export default api
