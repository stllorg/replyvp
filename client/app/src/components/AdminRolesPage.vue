<template>
    <div class="container py-5">
      <h1 class="text-center mb-4">Admin Roles</h1>
      <div class="table-responsive">
        <table class="table table-striped table-bordered">
          <thead class="table-dark">
            <tr>
              <th>ID</th>
              <th>Nome</th>
              <th>Roles</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="user in users" :key="user.id">
              <td>{{ user.id }}</td>
              <td>{{ user.name }}</td>
              <td>
                <div>
                  <div
                    v-for="role in roles"
                    :key="role"
                    class="form-check form-check-inline"
                  >
                    <input
                      class="form-check-input"
                      type="checkbox"
                      :id="`checkbox-${user.id}-${role}`"
                      :value="role"
                      v-model="user.assignedRoles"
                      @change="updateUserRoles(user.id, user.assignedRoles)"
                    />
                    <label
                      class="form-check-label"
                      :for="`checkbox-${user.id}-${role}`"
                    >
                      {{ role }}
                    </label>
                  </div>
                </div>
              </td>
              <td>
                <button
                  class="btn btn-danger btn-sm"
                  @click="deleteUser(user.id)"
                >
                  Remover
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </template>
  
  <script>
  import axios from "axios";
  
  export default {
    name: "AdminRolesPage",
    data() {
      return {
        users: [],
        roles: ["admin", "user", "editor"],
      };
    },
    async created() {
      try {
        // Autenticação JWT
        const token = localStorage.getItem("token");
        if (!token) {
          this.$router.push("/login");
          return;
        }
  
        // Fetching usuários
        const response = await axios.get(
          "http://localhost:8080/api/users/allusers.php",
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );
  
        if (response.data.success) {
          this.users = response.data.users.map(user => ({
            ...user,
            assignedRoles: user.roles || [], // Inicializar roles atribuídas
          }));
        } else {
          alert("Erro ao carregar os usuários.");
          this.$router.push("/dashboard");
        }
      } catch (err) {
        alert("Erro ao conectar ao servidor. Verifique sua conexão.");
        this.$router.push("/dashboard");
      }
    },
    methods: {
      async updateUserRoles(userId, newRoles) {
        try {
          const token = localStorage.getItem("token");
          const response = await axios.put(
            `http://localhost:8080/api/users/updateRoles.php`,
            { userId, roles: newRoles },
            {
              headers: {
                Authorization: `Bearer ${token}`,
              },
            }
          );
  
          if (!response.data.success) {
            alert("Erro ao atualizar as roles.");
          }
        } catch (err) {
          alert("Erro ao conectar ao servidor. Tente novamente mais tarde.");
        }
      },
      async deleteUser(userId) {
        if (confirm("Tem certeza que deseja remover este usuário?")) {
          try {
            const token = localStorage.getItem("token");
            const response = await axios.delete(
              `http://localhost:8080/api/users/deleteUser.php?userId=${userId}`,
              {
                headers: {
                  Authorization: `Bearer ${token}`,
                },
              }
            );
  
            if (response.data.success) {
              this.users = this.users.filter(user => user.id !== userId);
              alert("Usuário removido com sucesso.");
            } else {
              alert("Erro ao remover o usuário.");
            }
          } catch (err) {
            alert("Erro ao conectar ao servidor. Tente novamente mais tarde.");
          }
        }
      },
    },
  };
  </script>
  
  <style scoped>
  .table {
    margin-bottom: 2rem;
  }
  </style>