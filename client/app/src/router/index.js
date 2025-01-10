import { createRouter, createWebHistory } from 'vue-router';
import AppHome from '../components/AppHome.vue';
import ContactUs from '@/components/ContactUs.vue';
import MessageList from '@/components/MessageList.vue';

const routes = [
  { path: '/', name: 'AppHome', component: AppHome },
  { path: '/contact', name: 'ContactUs', component: ContactUs},
  { path: '/messages', name: 'MessageList', component: MessageList},
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
