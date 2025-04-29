<template>
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow">
          <div class="card-body p-4">
            <h2 class="card-title text-center mb-4">Página de Atualização Cadastral</h2>
            <form @submit.prevent="updateUser">
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
                <label for="old_password" class="form-label">Senha Atual</label>
                <input
                  type="password"
                  id="password"
                  class="form-control"
                  v-model="password"
                  placeholder="Digite sua senha atual"
                  required
                />
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">Endereço de email</label>
                <input
                  type="email"
                  id="new_email"
                  class="form-control"
                  v-model="newEmail"
                  placeholder="Digite seu endereço de email"
                  required
                />
              </div>
              <div class="mb-3">
                <label for="new_password" class="form-label">Nova Senha (Opcional)</label>
                <input
                  type="password"
                  id="new_password"
                  class="form-control"
                  v-model="newPassword"
                  placeholder="Digite sua nova senha"
                />
              </div>
              <div v-if="error" class="alert alert-danger mt-2" role="alert">
                {{ error }}
              </div>
              <div class="d-grid gap-2 mb-3">
                <button type="submit" class="btn btn-primary">Atualizar Cadastro</button>
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

const toast = useToast();
const router = useRouter();
const authStore = useAuthStore();

const user = authStore.user;

const username = ref("");
const email = ref("");
const password = ref("");
const newEmail = ref("");
const oldEmail = ref("");
const newPassword = ref("");
const oldPassword = ref("");
const permission = ref(false);
const error = ref(null);

const updateUser = async () => {
  error.value = null;

  if (
    !username.value ||
    !email.value ||
    !oldPassword.value ||
    !newEmail.value ||
    !newPassword.value ||
    !permission.value
  ) {
    error.value = "Todos os campos obrigatórios devem ser preenchidos.";
    return;
  }

  const response = userService.updateUser(
    user.value.id,
    oldPassword.value,
    oldEmail.value,
    newEmail.value,
    newPassword.value
  );

  if (response === 200) {
    toast.success("Seus dados foram atualizados com sucesso!", {
      timeout: 3000,
    });
    router.push("/dashboard");
  }
};
</script>
