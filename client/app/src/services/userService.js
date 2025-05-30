import api, { API_ENDPOINTS } from "./api";
import { getUserToken } from "@/services/authService";

export async function registerUser(username, email, password) {
  try{
  const response = await api.post(API_ENDPOINTS.AUTH.REGISTER, {
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
}
  
export async function updateUser(userId, password, email, newEmail, newPassword ) {
    const token = getUserToken();
    
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
      const response = await api.put(
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
}

export async function updateUserRoleById(userId) {
  const token = getUserToken();
    
  if (!token) {
    return false;
  }

  try {
    const response = await api.patch(API_ENDPOINTS.USERS.ROLES(userId), {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    });

    if (response.status === 204) {
      return response;
    }

  } catch (err) {
    console.error("Erro ao obter usuário:", error);
    throw error;
  }

}

export async function getUserById(userId) {
  const token = getUserToken();
    
  if (!token) {
    return false;
  }

  try{
    const response = await api.get(API_ENDPOINTS.USERS.BY_ID(userId), {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    });
    
    if (response.status === 200) {
      return response;
    }

  } catch(error) {
    console.error("Erro ao obter usuário:", error);
    throw error;
  }
}

export async function getAllUsers(page, usersPerPage = 15) {
  const token = getUserToken();
    
  if (!token) {
    return false;
  }

  try{
    const response = await api.get(API_ENDPOINTS.USERS.ROOT, {
      params: {
        page: page,
        limit: usersPerPage,
      }
    },
    {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    });
    
    if (response.status === 200) {
      // const users = response.data.data;
      // const totalUsersCount = response.data.pagination.totalUsers;
      // const totalPages = response.data.pagination.totalPages;
      return response;
    }
    
    } catch(error) {
      console.error("Erro ao obter usuários:", error);
      throw error;
    }
}

export async function getTicketsWithUserMessages(id = 0) {
  const token = getUserToken();

  if (!token) {
    return false;
  }

  try {
    id = 0;
    const response = await api.get(`${API_ENDPOINTS.USERS.INTERACTIONS(id)}`, {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    });

    if (response.status === 200) {
      return response;
    }
  } catch (error) {
    console.error("Erro ao buscar tickets:", error);
    throw error;
  }
}

export async function terminateAccount(userId) {
    const token = getUserToken();
    
    if (!token) {
      return false;
    }
    
    try {
      return api.delete(
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
}