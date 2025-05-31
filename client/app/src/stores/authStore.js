import { loginUser, validateToken } from "@/services/authService";
import { defineStore } from "pinia";

export const useAuthStore = defineStore("auth", {
  state: () => ({
    user: JSON.parse(localStorage.getItem("user")) || null,
  }),
  getters: {
    isUserLogged: (state) => !!state.user?.token,
    isUserStaff: (state) => ["admin", "manager", "support"].some((role) =>
      state.user?.roles?.includes(role)
    ),
    isRegularUser: (state) => state.user?.roles.includes('user'),
  },
  actions: {
    async login(username, password) {
      try {
        const response = await loginUser(username, password);
        if(response.status === 200){
          this.user = {
            id: response.data.user.id,
            username: response.data.user.username,
            roles: response.data.user.roles,
            token: response.data.token,
          };

          localStorage.setItem("user", JSON.stringify(this.user));
        } else {
          throw new Error("Falha ao autenticar. PAuth001");
        }

      } catch (error) {
        console.log("Erro ao realizar login. PAuth002", error.message);
      }
    },
    logout() {
      this.user = null;
      localStorage.removeItem("user");
    },
    async validateToken() {
      return await validateToken();
    }
  },
});