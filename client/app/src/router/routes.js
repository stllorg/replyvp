
// Public
import AppHome from '../views/HomePage.vue';
import ContactUs from '@/views/ContactUs.vue';
import TermsPage from '@/views/TermsPage.vue';
import PrivacyPolicyPage from '@/views/PrivacyPolicyPage.vue';
import NotFoundPage from '@/views/NotFoundPage.vue';
import RegisterPage from '@/views/RegisterPage.vue';
import LoginPage from '@/views/LoginPage.vue';

// Logged users
import DashboardPage from '@/views/DashboardPage.vue';

// User
import NewTicketPage from '@/views/user/NewTicketPage.vue';
import TerminateAccount from '@/views/user/TerminateAccount.vue';
import UpdateUserPage from '@/views/user/UpdateUserPage.vue';
import MessagesPage from '@/views/user/MessagesPage.vue';

// Support
import PendingTicketsList from '@/views/support/PendingTicketsList.vue';
import ReplyTicketPage from '@/views/support/ReplyTicketPage.vue';

// Ticket History & Search 
import TicketSearchPage from '@/components/pages/tickets/TicketSearchPage.vue';
import TicketHistoryPage from '@/components/pages/tickets/TicketHistoryPage.vue';

// Admin
import FinishedList from '@/views/admin/FinishedList.vue';
import AdminRolesPage from '@/views/admin/AdminRolesPage.vue';

export const routes = [
  { path: '/', name: 'AppHome', component: AppHome },
  { path: '/:catchAll(.*)', name: 'NotFoundPage', component: NotFoundPage},
  { path: '/contact', name: 'ContactUs', component: ContactUs},
  { path: '/login', name: 'LoginPage', component: LoginPage},
  { path: '/register', name: 'RegisterPage', component: RegisterPage},
  { path: '/policy', name: 'PrivacyPolicyPage', component: PrivacyPolicyPage},
  { path: '/terms', name: 'TermsPage', component: TermsPage},
  { path: '/dashboard', name: 'DashboardPage', component: DashboardPage, meta: { requiresAuth: true }},
  { path: '/tickets/search', name: 'TicketSearchPage', component: TicketSearchPage, meta: {requiresAuth: true }},
  { path: '/tickets/history', name: 'TicketHistoryPage', component: TicketHistoryPage, meta: {requiresAuth: true }},
  { path: '/admin/finished', name: 'FinishedList', component: FinishedList, meta: {requiresAdmin: true }},
  { path: '/admin/roles', name: 'AdminRolesPage', component: AdminRolesPage, meta: {requiresAdmin: true }},
  { path: '/support/pending', name: 'PendingTicketsList', component: PendingTicketsList, meta: {requiresSupport: true }},
  { path: '/support/reply', name: 'ReplyTicketPage', component: ReplyTicketPage, meta: {requiresSupport: true }},
  { path: '/user/terminate', name: 'TerminateAccount', component: TerminateAccount},
  { path: '/user/update', name: 'UpdateUserRegistrationPage', component: UpdateUserPage, meta: { requiresAuth: true }},
  { path: '/user/tickets/new', name: 'NewTicketPage', component: NewTicketPage },
  { path: '/user/messages', name: 'MessagesPage', component: MessagesPage, meta: { requiresAuth: true }},
];