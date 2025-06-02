import HomePage from '../views/HomePage.vue';
import TermsPage from '@/views/TermsPage.vue';
import PrivacyPolicyPage from '@/views/PrivacyPolicyPage.vue';
import NotFoundPage from '@/views/NotFoundPage.vue';

import DashboardPage from '@/views/DashboardPage.vue';

// User
import NewTicketPage from '@/views/user/NewTicketPage.vue';
import TerminateAccount from '@/views/user/TerminateAccount.vue';
import UpdateUserPage from '@/views/user/UpdateUserPage.vue';
import MessagesPage from '@/views/user/MessagesPage.vue';

// Admin
import AdminRolesPage from '@/views/admin/AdminRolesPage.vue';
import StaffAreaPage from '@/views/StaffAreaPage.vue';

export const routes = [
  { path: '/', name: 'HomePage', component: HomePage },
  { path: '/:catchAll(.*)', name: 'NotFoundPage', component: NotFoundPage},
  { path: '/login', redirect: '/'},
  { path: '/register', redirect: '/'},
  { path: '/policy', name: 'PrivacyPolicyPage', component: PrivacyPolicyPage},
  { path: '/terms', name: 'TermsPage', component: TermsPage},
  { path: '/dashboard', name: 'DashboardPage', component: DashboardPage, children: [
  { path:'login', name: 'Login', component: HomePage, props: { initialTab: 'login' } }, { path: 'register', name: 'Register', component: HomePage, props: { initialTab: 'register' }}], meta: { requiresAuth: true }},
  { path: '/staff/', name: 'StaffAreaPage', component: StaffAreaPage, meta: {requiresAdmin: true }},
  { path: '/staff/accounts', name: 'AdminRolesPage', component: AdminRolesPage, meta: {requiresAdmin: true }},
  { path: '/user/update', name: 'UpdateUserRegistrationPage', component: UpdateUserPage, meta: { requiresAuth: true }},
  { path: '/user/tickets/new', name: 'NewTicketPage', component: NewTicketPage },
  { path: '/user/messages', name: 'MessagesPage', component: MessagesPage, meta: { requiresAuth: true }},
  { path: '/user/terminate', name: 'TerminateAccount', component: TerminateAccount},
];