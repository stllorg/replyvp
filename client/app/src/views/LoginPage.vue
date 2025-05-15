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
                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" checked>
                <label class="form-check-label ms-1" for="flexSwitchCheckChecked">Fazer login</label>
              </div>
            </div>
            <form @submit.prevent="handleUserSignIn">
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
                <input
                  type="password"
                  id="password"
                  class="form-control"
                  v-model="password"
                  placeholder=""
                  required
                />
                <label for="password" class="form-label">Senha</label>
              </div>
              <div v-if="error" class="alert alert-danger mt-2" role="alert">
                {{ error }}
              </div>
              <div class="d-grid gap-2 mb-3">
                <button type="submit" class="btn btn-primary">Fazer login</button>
              </div>
              <div class="mt-2" style="display: flex; flex-direction: column; align-items: center;">
                <i class="bi bi-key"></i>
                <i class="bi bi-arrow-clockwise"></i>
                <p>Não consegue entrar em sua conta?
                  <a href="#">Recuperar acesso</a>
                </p>
              </div>
              <p class="text-center">
                Ainda não tem cadastro?
                <router-link to="/register">Cadastre-se</router-link>
              </p>
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
import { useAuthStore } from "@/stores/authStore";
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";

const router = useRouter();
const username = ref("");
const password = ref("");
const error = ref(null);
const authStore = useAuthStore();

onMounted(() => {
  if (authStore.isUserLogged) {
    console.log(1);
    router.push("/dashboard");
  }
});

const handleUserSignIn = async () => {
  error.value = null;

  try {
    await authStore.login(username.value, password.value);
    router.push("/dashboard");
  } catch (err) {
    error.value = "Erro ao tentar fazer login.";
  }
  // Clear fields to force retype login info
  username.value = "";
  password.value = "";
};
</script>
