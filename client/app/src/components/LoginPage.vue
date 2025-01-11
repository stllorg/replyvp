<template>
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow">
          <div class="card-body p-4">
            <h2 class="card-title text-center mb-4">Entrar em IA.ContactCenter</h2>
            <form @submit.prevent="testLogin">
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
                Ainda não tem cadastro? <a href="#">Cadastre-se</a>
              </p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
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
    testLogin() {
      this.error = null;
      if (this.username === 'admin' && this.password === 'admin') {
        alert(`Login bem sucedido!\n Você se conectou como ${this.username}`);
      } else {
        this.error = "Credenciais inválidas.";
      }
      this.username = "";
      this.password = "";
    },
  },
};
</script>