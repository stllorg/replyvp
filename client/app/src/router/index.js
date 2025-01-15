import { createRouter, createWebHistory } from 'vue-router';
import AppHome from '../components/AppHome.vue';
import NotFoundPage from '@/components/NotFoundPage.vue';
import ContactUs from '@/components/ContactUs.vue';
import MessageList from '@/components/MessageList.vue';
import LoginPage from '@/components/LoginPage.vue';
import RegisterPage from '@/components/RegisterPage.vue';
import TerminateAccount from '@/components/TerminateAccount.vue';
import PrivacyPolicyPage from '@/components/PrivacyPolicyPage.vue';
import TermsPage from '@/components/TermsPage.vue';
import DashboardPage from '@/components/DashboardPage.vue';
import FinishedList from '@/components/FinishedList.vue';
import UnansweredList from '@/components/UnansweredList.vue';
import MessageHistoryPage from '@/components/MessageHistoryPage.vue';
import AdminRolesPage from '@/components/AdminRolesPage.vue';

const routes = [
  { path: '/', name: 'AppHome', component: AppHome },
  { path: '/:catchAll(.*)', name: 'NotFoundPage', component: NotFoundPage},
  { path: '/contact', name: 'ContactUs', component: ContactUs},
  { path: '/messages', name: 'MessageList', component: MessageList},
  { path: '/login', name: 'LoginPage', component: LoginPage},
  { path: '/register', name: 'RegisterPage', component: RegisterPage},
  { path: '/terminate', name: 'TerminateAccount', component: TerminateAccount},
  { path: '/policy', name: 'PrivacyPolicyPage', component: PrivacyPolicyPage},
  { path: '/terms', name: 'TermsPage', component: TermsPage},
  { path: '/dashboard', name: 'DashboardPage', component: DashboardPage, meta: { requiresAuth: true }},
  { path: '/finished', name: 'FinishedList', component: FinishedList, meta: {requiresAdmin: true}},
  { path: '/pending', name: 'UnansweredList', component: UnansweredList, meta: {requiresSupport: true}},
  { path: '/history', name: 'MessageHistoryPage', component: MessageHistoryPage, meta: {requiresSupport: true}},
  { path: '/roles', name: 'AdminRolesPage', component: AdminRolesPage, meta: {requiresAdmin: true}}
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const requiresAdmin = to.matched.some((record) => record.meta.requiresAdmin);
  const requiresManager = to.matched.some((record) => record.meta.requiresManager);
  const requiresSupport = to.matched.some((record) => record.meta.requiresSupport);
  const requiresAuth = to.matched.some((record) => record.meta.requiresAuth);
  const user = JSON.parse(localStorage.getItem("user"));

  if (requiresAuth) {
    if(!user || !user.token){
      next("/login");
      console.login("Info: The user is not logged in.")
    } else {
      next();
    }
  }

  if (requiresAdmin) { 
    if (!user || !user.roles) {
      console.log("Info: The user does not have Admin permission.")
      return next({ path: '/' });
    } else if (!user.roles.includes('admin')) {
      return next({path: '/'});
    } else {
      return next;
    }
  }

  if (requiresManager) {
    if (!user || !user.roles) {
      console.log("Info: the user does not have Manager permission.")
    } else if (!user.roles.includes('admin') || !user.roles.includes('manager')) {
      return next ({path: '/'});
    } else {
      return next;
    }
  }

  if (requiresSupport) {
    if (!user || !user.roles) {
      console.log("Info: the user does not have Support permission.")
    } else if (!user.roles.includes('admin') || !user.roles.includes('manager') || !user.roles.includes('support')) {
      return next ({path: '/'});
    } else {
      return next;
    }
  }

});

export default router;