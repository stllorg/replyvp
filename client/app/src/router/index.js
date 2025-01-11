import { createRouter, createWebHistory } from 'vue-router';
import AppHome from '../components/AppHome.vue';
import ContactUs from '@/components/ContactUs.vue';
import MessageList from '@/components/MessageList.vue';
import LoginPage from '@/components/LoginPage.vue';

const routes = [
  { path: '/', name: 'AppHome', component: AppHome },
  { path: '/contact', name: 'ContactUs', component: ContactUs},
  { path: '/messages', name: 'MessageList', component: MessageList},
  { path: '/login', name: 'LoginPage', component: LoginPage},
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;