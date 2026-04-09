import { createRouter, createWebHistory } from 'vue-router'
import LandingView from './views/LandingView.vue'
import AppView from './views/AppView.vue'

const routes = [
  { path: '/',    name: 'landing', component: LandingView },
  { path: '/app', name: 'app',     component: AppView },
]

export default createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) return savedPosition
    if (to.hash) return { el: to.hash, behavior: 'smooth' }
    return { top: 0 }
  },
})
