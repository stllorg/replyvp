import { useAuthStore } from "@/stores/authStore";
import api, { API_ENDPOINTS } from "./api";

export function getUserToken() {
  const authStore = useAuthStore();
  return authStore.user?.token || null;
}

export async function validateToken() {
  const token = getUserToken();

  if (!token) {
    return false;
  }

  try {
    const response = await api.get(API_ENDPOINTS.AUTH.AUTHENTICATE, {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    });

    return response.data.success;
  } catch (error) {
    console.error("Token inválido ou expirado:", error.response?.data || error.message);
    localStorage.removeItem("user");
    return false;
  }
}

export async function loginUser(username, password) {
  try {
    const response = await api.post(API_ENDPOINTS.AUTH.LOGIN, { username, password });

    if (response.status === 200) {
      const user = response.data.user;
      const token = response.data.token;
      return {
        status: response.status,
        data: {
          user: user,
          token: token,
        },
      };
    } else {
      return { success: false, error: "Erro ao tentar fazer login." };
    }
  } catch (err) {
    return { success: false, error: "Erro na comunicação com o servidor." };
  }
}

export function redirectAfterLogin(router) {
  const authStore = useAuthStore();

  try {
    if (authStore.isUserLogged) {
      if (authStore.isUserStaff) {
        router.push("/staff");
      } else {
        router.push("/dashboard");
      }
    }
  } catch (err) {
    console.log("Failed to redirect logged user",err);
  }
}

export function handleLogout(router) {
  const authStore = useAuthStore();
  
  authStore.logout();
  router.push("/");
}