import HomePage from '../views/HomePage.vue';
import ContactUs from '@/views/ContactUs.vue';
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

export const routes = [
  { path: '/', name: 'HomePage', component: HomePage },
  { path: '/:catchAll(.*)', name: 'NotFoundPage', component: NotFoundPage},
  { path: '/contact', name: 'ContactUs', component: ContactUs},
  { path: '/login', redirect: '/'},
  { path: '/register', redirect: '/'},
  { path: '/policy', name: 'PrivacyPolicyPage', component: PrivacyPolicyPage},
  { path: '/terms', name: 'TermsPage', component: TermsPage},
  { path: '/user/terminate', name: 'TerminateAccount', component: TerminateAccount},
  { path: '/dashboard', name: 'DashboardPage', component: DashboardPage, meta: { requiresAuth: true }},
  { path: '/admin/roles', name: 'AdminRolesPage', component: AdminRolesPage, meta: {requiresAdmin: true }},
  { path: '/user/update', name: 'UpdateUserRegistrationPage', component: UpdateUserPage, meta: { requiresAuth: true }},
  { path: '/user/tickets/new', name: 'NewTicketPage', component: NewTicketPage },
  { path: '/user/messages', name: 'MessagesPage', component: MessagesPage, meta: { requiresAuth: true }},
];