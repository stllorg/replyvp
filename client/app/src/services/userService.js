import axios from "axios";

const USERS_API_URL = "http://localhost:8080/api/users";

const userService = {
  
  async registerUser(username, email, password) {
    try{
    const response = await axios.post(`${USERS_API_URL}/register.php`, {
        username: username,
        email: email,
        password: password,
      });
    if (response.status === 201) {
     return response.status;
    }
    } catch(error) {
      console.error("Erro ao registrar usuário:", error);
      throw error;
    }
  },
  async updateUser(userId, password, email, newEmail, newPassword ) {
    const updatedData = {
      id: userId,
      old_email: email,
      old_password: password,
      new_email: newEmail,
      new_password: newPassword || undefined,
    };
    try {
      const response = await axios.put(
       `${USERS_API_URL}/update.php`,
        updatedData
      );
      if (response.status === 200) {
       return response.status;
      }
    } catch (error) {
      console.error("Erro ao registrar usuário", error);
      throw error;
    }
  },
  terminateAccount(userId) {
    return axios.delete(`${USERS_API_URL}/delete.php`, {
      data: { id: userId },
  });
  },
};

export default userService;