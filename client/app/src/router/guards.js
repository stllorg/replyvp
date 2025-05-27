import { useToast } from "vue-toastification";

const toast = useToast();

export function setupGuards(router) {
  router.beforeEach((to, from, next) => {
    const user = JSON.parse(localStorage.getItem("user"));
    const requiresAdmin = to.matched.some((record) => record.meta.requiresAdmin);
    const requiresManager = to.matched.some((record) => record.meta.requiresManager);
    const requiresSupport = to.matched.some((record) => record.meta.requiresSupport);
    const requiresAuth = to.matched.some((record) => record.meta.requiresAuth);

    if (requiresAuth) {
      if (!user || !user.token) {
        toast.warning('Necessário fazer login!', { timeout: 3000 });
        next("/login");
      }
    }

    if (requiresAdmin) {
      if (!user || !user.roles.includes('admin')) {
        toast.error('Conteúdo com acesso restrito para administradores!', { timeout: 3000 });
        next({ path: '/' });
      }
    }

    if (requiresManager) {
      if (!user || (!user.roles.includes('admin') && !user.roles.includes('manager'))) {
        toast.error('Conteúdo restrito para administradores!', { timeout: 3000 });
        return next({ path: '/' });
      }
    }

    if (requiresSupport) {
      if (!user || (!user.roles.includes('admin') && !user.roles.includes('manager') && !user.roles.includes('support'))) {
        toast.error('Conteúdo restrito para funcionários!', { timeout: 3000 });
        next({ path: '/' });
      }
    }

    return next();
  });

}