import { createRouter, createWebHistory } from 'vue-router';
import AppHome from '../components/AppHome.vue';
import ContactUs from '@/components/ContactUs.vue';
import MessageList from '@/components/MessageList.vue';
import LoginPage from '@/components/LoginPage.vue';
import RegisterPage from '@/components/RegisterPage.vue';
import TerminateAccount from '@/components/TerminateAccount.vue';
import PrivacyPolicyPage from '@/components/PrivacyPolicyPage.vue';
import TermsPage from '@/components/TermsPage.vue';

const routes = [
  { path: '/', name: 'AppHome', component: AppHome },
  { path: '/contact', name: 'ContactUs', component: ContactUs},
  { path: '/messages', name: 'MessageList', component: MessageList},
  { path: '/login', name: 'LoginPage', component: LoginPage},
  { path: '/register', name: 'RegisterPage', component: RegisterPage},
  { path: '/terminate', name: 'TerminateAccount', component: TerminateAccount},
  { path: '/policy', name: 'PrivacyPolicyPage', component: PrivacyPolicyPage},
  { path: '/terms', name: 'TermsPage', component: TermsPage},
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;