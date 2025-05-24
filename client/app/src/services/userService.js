import axios from "axios";
import { API_ENDPOINTS } from "./api";
import authService from "@/services/authService";

const userService = {
  
  async registerUser(username, email, password) {
    try{
    const response = await axios.post(API_ENDPOINTS.AUTH.REGISTER, {
        username: username,
        email: email,
        password: password,
      });
    if (response.status === 201) {
     return response;
    }
    } catch(error) {
      console.error("Erro ao registrar usuário:", error);
      throw error;
    }
  },
  async updateUser(userId, password, email, newEmail, newPassword ) {
    const token = authService.getUserToken();
    
    if (!token) {
      return false;
    }

    const updatedData = {
      id: userId,
      old_email: email,
      old_password: password,
      new_email: newEmail,
      new_password: newPassword || undefined,
    };
    try {
      const response = await axios.put(
        API_ENDPOINTS.USERS.BY_ID(userId), {
           headers: {
            Authorization: `Bearer ${token}`,
          },
        },
        updatedData
      );
      if (response.status === 200) {
       return response.status;
      }
    } catch (error) {
      console.error("Erro ao atualizar usuário", error);
      throw error;
    }
  },
  terminateAccount(userId) {
    const token = authService.getUserToken();
    
    if (!token) {
      return false;
    }
    
    try {
      return axios.delete(
        API_ENDPOINTS.USERS.BY_ID(userId), {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        },
      );
    } catch (error) {
      console.error("Erro ao encerrar conta de usuário", error);
      throw error;
    }
  },
};

export default userService;