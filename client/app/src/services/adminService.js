import axios from "axios";
import api, { API_ENDPOINTS } from "./api";

const ADMIN_API_URL = "http://localhost:8080/api/users";

const adminService = {
  
  async getAllUsers(token) {
    try {

      const response = await axios.get(
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

  async updateUserRoles(userId, newRoles, token) {
    try {
      const response = await axios.put(
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
  async deleteUserAsAdmin(token) {
    try {
      const response = await axios.delete(
       API_ENDPOINTS.USERS.BY_ID(userId),
        {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        }
      );

      if (response.data.success) {
        this.users = this.users.filter((user) => user.id !== userId);
        alert("Usuário removido com sucesso.");
      } else {
        alert("Erro ao remover o usuário.");
      }
    } catch (error) {
      console.log(error);
    }
  }
    
};

export default adminService;