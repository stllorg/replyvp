import api, { API_ENDPOINTS } from "./api";
import { getUserToken } from "@/services/authService";

const adminService = {
  
  async getAllUsers() {
    const token = getUserToken();
    
    if (!token) {
      return false;
    }

    try {

      const response = await api.get(
        API_ENDPOINTS.TICKETS.USERS,
        {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        }
      );

    if (response.status === 200) {
      return response.data;
    } else {
      return response;
    } 
  } catch (error) {
    console.log(error);
  }
  },
  async updateUserRoles(userId, newRoles) {
    const token = getUserToken();
    
    if (!token) {
      return false;
    }

    try {
      const response = await api.put(
        API_ENDPOINTS.USERS.ROLES(userId), {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        },
        { 
          roles: newRoles 
        }
      );
      if (!response.data.success) {
        console.log("Erro ao atualizar as roles.");
      }
    } catch (error) {
      console.log(error);
    }
  },
  async deleteUserAsAdmin(userId) {
    const token = getUserToken();
    
    if (!token) {
      return false;
    }
    
    try {
      const response = await api.delete(
       API_ENDPOINTS.USERS.BY_ID(userId),
        {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        }
      );

      if (response.data.success) {
        alert("Usuário removido com sucesso.");
        return response;
      } else {
        alert("Erro ao remover o usuário.");
        return response;
      }
    } catch (error) {
      console.log(error);
      throw error;
    }
  },
};

export default adminService;