<template>
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow">
          <div class="card-body p-4">
            <h2 class="card-title text-center mb-4">
              Apagar sua conta em IA.ContactCenter
            </h2>
            <form @submit.prevent="handleDeleteAccount">
              <div class="mb-3">
                <label for="email" class="form-label"
                  >Confirme o seu endereço de email</label
                >
                <input
                  type="email"
                  id="email"
                  class="form-control"
                  v-model="email"
                  placeholder="Email"
                  required
                />
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Confirme sua senha</label>
                <input
                  type="password"
                  id="password"
                  class="form-control"
                  v-model="password"
                  placeholder="Senha"
                  required
                />
              </div>
              <div class="mb-3">
                <input
                  type="checkbox"
                  id="confirmation"
                  class="form-check-input"
                  v-model="confirmation"
                  required
                />
                <label class="form-check-label" for="confirmation">
                  <a>Confirmo que desejo marcar minha conta para exclusão.</a>
                </label>
              </div>
              <div v-if="error" class="alert alert-danger mt-2" role="alert">
                {{ error }}
              </div>
              <div class="d-grid gap-2 mb-3">
                <button type="submit" class="btn btn-danger">Excluir conta</button>
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

const authStore = useAuthStore();

const user = authStore.user;

const router = useRouter();
const toast = useToast();

const password = ref("");
const email = ref("");
const confirmation = ref(false);
const error = ref(null);

const handleDeleteAccount = () => {
  // TODO: Implement method to delete account
  if (this.confirmation) {
    deleteUserAccount();
  } else {
    alert(`Operação cancelada! \n Retornando para página inicial!`);
  }
};

const deleteUserAccount = () => {
  userService
    .terminateAccount(user.value.id, email.value, password.value)
    .then((response) => {
      toast.success("Sucesso ao encerrar a conta!", { timeout: 3000 });
      console.log("O cadastro do usuário foi apagado com sucesso", response.data);
      router.push("/");
    })
    .catch((err) => {
      toast.error("Falha ao encerrar a conta!", { timeout: 3000 });
      console.error("Falha ao deletar o recurso", err);
      error.value = "Ocorreu uma falha ao deletar sua conta";
    });
};
</script>
