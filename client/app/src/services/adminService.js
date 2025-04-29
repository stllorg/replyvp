import axios from "axios";

const ADMIN_API_URL = "http://localhost:8080/api/users";

const adminService = {
  
  async getAllUsers(token) {
    try {

      const response = await axios.get(
        `${ADMIN_API_URL}/all_users.php`,
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
        `${ADMIN_API_URL}/update_roles.php`,
        { userId, roles: newRoles },
        {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        }
      );
      if (!response.data.success) {
        console.log("Erro ao atualizar as roles.");
      }
    } catch (error) {
      console.log(error);
    }
  },
  async deleteUserAsAdmin(userId, token) {
    try {
      const response = await axios.delete(
       `${ADMIN_API_URL}/delete_user.php?userId=${userId}`,
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