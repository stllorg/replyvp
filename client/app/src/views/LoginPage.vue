<template>
  <div class="col-12">
    <div class="main-card">
      <div class="col-md-4 py-4 main-concept">
        <div class="card shadow">
          <div class="card-body p-4">
            <h2 class="card-title text-center mb-4">Login</h2>
            <ul class="nav nav-tabs mb-4 justify-content-center">
              <li class="nav-item">
                <a class="nav-link active" href="#">Login</a>
              </li>
              <li class="nav-item">
                <router-link to="/register" class="nav-link">Registro</router-link>
              </li>
            </ul>
            <form @submit.prevent="handleUserSignIn">
              <div class="form-floating mb-3">
                <input
                type="text"
                id="username"
                class="form-control"
                v-model="username"
                placeholder=""
                required
                />
                <label for="username" class="form-label">E-mail de login</label>
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
                <p>Não consegue entrar em sua conta?
                  <a href="#">Recuperar acesso</a>
                </p>
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
