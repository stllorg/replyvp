<template>
  <div class="col-12">
    <div class="main-card">
      <div class="col-md-4 py-4 main-concept">
        <div class="card shadow">
          <div class="card-body p-4">
            <h2 class="card-title text-center mb-4"></h2>
            <div class="connection-switch d-flex">
              <span>Criar conta</span>
              <div class="form-check form-switch ms-2">
                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                <label class="form-check-label ms-1" for="flexSwitchCheckDefault">Fazer login</label>
              </div>
            </div>
            <form @submit.prevent="register">
              <div class="form-floating mb-3">
                <label for="username" class="form-label">Nome de usuário</label>
                <input
                  type="text"
                  id="username"
                  class="form-control"
                  v-model="username"
                  placeholder=""
                  required
                />
              </div>
              <div class="form-floating mb-3">
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
              <div class="form-floating mb-3">
                <label for="password" class="form-label">Senha</label>
                <input
                  type="password"
                  id="password"
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
                  Concordo com os
                  <a href="#" target="_blank" rel="noopener noreferrer">Termos de Uso</a>
                  e a
                  <a href="#" target="_blank" rel="noopener noreferrer"
                    >Política de Privacidade</a
                  >.
                </label>
              </div>
              <div v-if="error" class="alert alert-danger mt-2" role="alert">
                {{ error }}
              </div>
              <div class="d-grid gap-2 mb-3">
                <button type="submit" class="btn btn-primary">Cadastrar-se</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="col-4">
        <img
        src="/images/freepik-customer-support-flat-design-illustration.png"
        class="illustration"
        alt="Illustration - Customer Support ( Flat design)"
        />
      </div>
    </div>
  </div>
</template>

<style scoped>
.main-card {
  background-color: deepskyblue;
  min-height: 100vh;
}

.main-concept {
  color: black;
  padding: 20px;
  position: absolute;
  top: 25%;
  left: 60%;
  border-radius: 10px;
  text-align: left;
  z-index: 20;
}

.illustration {
  position: absolute;
  top: 55%;
  left: 25%;
  transform: translate(-50%, -50%);
  z-index: 10;
  max-width: 60%;
  max-height: 90%;
}
</style>

<script setup>
import userService from "@/services/userService";
import { useAuthStore } from "@/stores/authStore";
import { ref } from "vue";
import { useRouter } from "vue-router";
import { useToast } from "vue-toastification";

const router = useRouter();
const toast = useToast();
const authStore = useAuthStore();

const user = authStore.user;

const username = ref("");
const password = ref("");
const email = ref("");
const permission = ref(false);
const error = ref(null);

const register = async () => {
  error.value = null;
  if (!username.value || !password.value || !email.value) {
    error.value = "Todos os campos são obrigatórios.";
    return;
  }
  const response = await userService.registerUser(username.value, email.value, password.value);
  if (response.status === 201) {
    toast.sucess(`Você se registrou com sucesso ${username.value}`, { timeout: 6000 });
    username.value = "";
    password.value = "";
    email.value = "";
    router.push("/dashboard");
  } else {
    error.value = response.error || "Erro ao registar usuário.";
  }
};
if (user && user.token) {
  router.push("/dashboard");
}
</script>
