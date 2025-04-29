<template>
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow">
          <div class="card-body p-4">
            <h2 class="card-title text-center mb-4">Registrar em IA.ContactCenter</h2>
            <form @submit.prevent="register">
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
    </div>
  </div>
</template>

<script setup>
import userService from "@/services/UserService";
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
  const response = await userService.registerUser();
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
