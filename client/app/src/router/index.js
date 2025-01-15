import { createRouter, createWebHistory } from 'vue-router';
import AppHome from '../components/AppHome.vue';
import ContactUs from '@/components/ContactUs.vue';
import MessageList from '@/components/MessageList.vue';
import LoginPage from '@/components/LoginPage.vue';
import RegisterPage from '@/components/RegisterPage.vue';
import TerminateAccount from '@/components/TerminateAccount.vue';
import PrivacyPolicyPage from '@/components/PrivacyPolicyPage.vue';
import TermsPage from '@/components/TermsPage.vue';
import DashboardPage from '@/components/DashboardPage.vue';

const routes = [
  { path: '/', name: 'AppHome', component: AppHome },
  { path: '/contact', name: 'ContactUs', component: ContactUs},
  { path: '/messages', name: 'MessageList', component: MessageList},
  { path: '/login', name: 'LoginPage', component: LoginPage},
  { path: '/register', name: 'RegisterPage', component: RegisterPage},
  { path: '/terminate', name: 'TerminateAccount', component: TerminateAccount},
  { path: '/policy', name: 'PrivacyPolicyPage', component: PrivacyPolicyPage},
  { path: '/terms', name: 'TermsPage', component: TermsPage},
  { path: '/dashboard', name: 'DashboardPage', component: DashboardPage, meta: { requiresAuth: true }},
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const requiresAuth = to.matched.some((record) => record.meta.requiresAuth);
  const user = JSON.parse(localStorage.getItem("user"));

  if (requiresAuth && (!user || !user.token)) {
    next("/login");
    console.login("Info: The user is not logged in.")
  } else {
    next();
  }
});

export default router;