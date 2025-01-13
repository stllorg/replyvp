<template>
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow">
          <div class="card-body p-4">
            <h2 class="card-title text-center mb-4">Registrar em IA.ContactCenter</h2>
            <form @submit.prevent="testSignUp">
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
                <label for="email" class="form-label">Endereço de email</label>
                <input
                  type="email"
                  id="email"
                  class="form-control"
                  v-model="email"
                  placeholder="Digite seu endereço de email"
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
              </div>
              <div class="mb-3">
                <input
                  type="checkbox"
                  id="permission"
                  class="form-check-input"
                  v-model="permission"
                  required
                />
                <label class="form-check-label" for="permission">
                  Concordo com os <a href="#" target="_blank" rel="noopener noreferrer">Termos de Uso</a> e a <a href="#" target="_blank" rel="noopener noreferrer">Política de Privacidade</a>.
                </label>
              </div>
              <div v-if="error" class="alert alert-danger mt-2" role="alert">
                {{ error }}
              </div>
              <div class="d-grid gap-2 mb-3">
                <button type="submit" class="btn btn-primary">Fazer login</button>
              </div>
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
  name: 'RegisterPage',
  data() {
    return {
      username: "",
      password: "",
      email: "",
      permission: false,
      error: null
    };
  },
  methods: {
    async register() {
      this.error = null;

      if (!this.username || !this.password || !this.email) {
        this.error = "Todos os campos são obrigatórios.";
        return;
      }

      try{
        const response = await axios.post('http://localhost:8080/api/users/register.php', {
          username: this.username,
          email: this.email,
          password: this.password,
        });

      if (response.status === 200) {
        alert(`Usuário ${this.username} cadastrado com sucesso!`);
        this.username = "";
        this.password = "";
        this.email = "";
      }
      } catch(error) {
        if (error.response) {
          this.error = error.response.data.message || 'Erro ao registrar usuário';
        } else {
          this.error = 'Erro de rede ou servidor';
        }
      }
    }
  },
};
</script>