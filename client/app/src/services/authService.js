import axios from "axios";
import { useAuthStore } from "@/stores/authStore";

const AUTH_URL = "http://localhost:8080/auth";
const validationUrl = `${AUTH_URL}/authenticate`;
const loginUrl = `${AUTH_URL}/login`;

export function getUserToken() {
  const authStore = useAuthStore();
  return authStore.user?.token || null;
}

export async function validateToken(token) {

  if (!token) {
    return false;
  }

  try {
    const response = await axios.get(validationUrl, {
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
    const response = await axios.post(loginUrl, { username, password });

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