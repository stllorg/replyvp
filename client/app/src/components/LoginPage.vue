<template>
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow">
          <div class="card-body p-4">
            <h2 class="card-title text-center mb-4">Entrar em IA.ContactCenter</h2>
            <form @submit.prevent="loginUser">
              <div class="mb-3">
                <label for="username" class="form-label">Nome de usuário</label>
                <input
                  type="text"
                  id="username"
                  class="form-control"
                  v-model="username"
                  placeholder="Digite seu nome de usuário"
                  required
                />
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Senha</label>
                <input
                  type="password"  id="password"
                  class="form-control"
                  v-model="password"
                  placeholder="Digite sua senha"
                  required
                />
                <p class="mt-2">
                Não consegue entrar em sua conta? <a href="#">Recuperar acesso</a>
                </p>
              </div>
              <div v-if="error" class="alert alert-danger mt-2" role="alert">
                {{ error }}
              </div>
              <div class="d-grid gap-2 mb-3">
                <button type="submit" class="btn btn-primary">Fazer login</button>
              </div>
              <p class="text-center">
                Ainda não tem cadastro? <router-link to="/register">Cadastre-se</router-link>
              </p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'LoginPage',
  data() {
    return {
      username: "",
      password: "",
      error: null
    };
  },
  methods: {
    async loginUser() {
      this.error = null;
      try {
        const response = await axios.post("http://localhost:8080/api/users/login.php", {
          username: this.username,
          password: this.password,
        });

        if (response.data.success) {
          const user = response.data.user;
          localStorage.setItem("user", JSON.stringify({
            username: user.username,
            email: user.email,
          }));

          this.$router.push("/dashboard");
        } else {
          const errorData = await response.json();
          this.error = errorData.error || "Erro ao tentar fazer login.";
        }
      } catch (err) {
        this.error = "Erro na comunicação com o servidor. Tente novamente mais tarde.";
      } finally {
        this.username = "";
        this.password = "";
      }
    },
  },
};
</script>