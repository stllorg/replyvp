<template>
  <div class="col-12">
    <div class="card mb-2 main-card">
      <div class="col-md-4 py-4 main-concept2">
        <div class="card shadow">
          <div v-if="isLoginTabActive" class="card-body p-4">
            <h2 class="card-title text-center mb-4">Login</h2>
            <ul class="nav nav-tabs mb-4 justify-content-center">
              <li class="nav-item">
                <a class="nav-link active" href="#">Já tem uma conta?</a>
              </li>
              <li class="nav-item">
                <a
                  class="nav-link"
                  :class="{ active: isRegisterTabActive }"
                  href="#"
                  @click.prevent="setActiveTab('register')"
                  >Criar conta</a
                >
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
                <label for="username" class="form-label">Nome de usuário</label>
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
                <button type="submit" class="btn btn-primary">
                  Fazer login
                </button>
              </div>
              <div
                class="mt-2"
                style="
                  display: flex;
                  flex-direction: column;
                  align-items: center;
                "
              >
                <p>
                  Não consegue entrar em sua conta?
                  <a href="#">Recuperar acesso</a>
                </p>
              </div>
            </form>
          </div>
          <div v-if="isRegisterTabActive" class="card-body p-4">
            <h2 class="card-title text-center mb-4">Registro</h2>
            <ul class="nav nav-tabs mb-4 justify-content-center">
              <li class="nav-item">
                <a
                  class="nav-link"
                  :class="{ active: isLoginTabActive }"
                  href="#"
                  @click.prevent="setActiveTab('login')"
                  >Já tem uma conta?</a
                >
              </li>
              <li class="nav-item">
                <a class="nav-link active" href="#">Criar conta</a>
              </li>
            </ul>
            <form @submit.prevent="register">
              <div class="form-floating mb-3">
                <input
                  type="text"
                  id="username"
                  class="form-control"
                  v-model="username"
                  placeholder=""
                  required
                />
                <label for="username" class="form-label">Nome de usuário</label>
              </div>
              <div class="form-floating mb-3">
                <input
                  type="email"
                  id="email"
                  class="form-control"
                  v-model="email"
                  placeholder="Digite seu endereço de email"
                  required
                />
                <label for="email" class="form-label">Endereço de email</label>
              </div>
              <div class="form-floating mb-3">
                <input
                  type="password"
                  id="password"
                  class="form-control"
                  v-model="password"
                  placeholder="Digite sua senha"
                  required
                />
                <label for="password" class="form-label">Senha</label>
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
                  <a href="#" target="_blank" rel="noopener noreferrer"
                    >Termos de Uso</a
                  >
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
                <button type="submit" class="btn btn-primary">
                  Cadastrar-se
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div
        v-if="isVisible"
        class="card-body col-8 main-concept"
        :class="{ 'fade-out-up': animateAndHide }"
      >
        <h5 class="card-title">Precisa de ajuda?</h5>
        <p class="card-text">
          Estamos prontos para analisar seu caso e resolver seu problema.
        </p>
      </div>
      <div
        v-if="isVisibleB"
        class="card-body col-8 main-concept"
        :class="{ 'fade-out-up': animateAndHide }"
      >
        <h5 class="card-title">Está com pressa?</h5>
        <p class="card-text">
          Abra seu ticket e faça suas coisas enquanto quando resolvemos seu
          problema.
        </p>
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
  min-height: 60vh;
}

.illustration {
  position: absolute;
  top: 40%;
  left: 65%;
  transform: translate(-50%, -50%);
  z-index: 10;
  max-width: 60%;
  max-height: 90%;
}

.main-concept {
  color: black;
  padding: 20px;
  position: absolute;
  top: 25%;
  left: 5%;
  border-radius: 10px;
  text-align: left;
  z-index: 20;
}

.main-concept2 {
  color: black;
  padding: 20px;
  position: absolute;
  top: 25%;
  left: 60%;
  border-radius: 10px;
  text-align: left;
  z-index: 20;
}

.fade-out-up {
  animation: slideUpAndFadeOut 5s forwards;
}

@keyframes slideUpAndFadeOut {
  0% {
    top: 25%;
    opacity: 1;
  }
  40% {
    top: 2%;
    opacity: 1;
  }
  100% {
    top: 2%;
    opacity: 0;
  }
}
</style>

<script setup>
import { useAuthStore } from "@/stores/authStore";
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import { useToast } from "vue-toastification";
import userService from "@/services/userService";

const router = useRouter();
const toast = useToast();
const authStore = useAuthStore();
const isVisible = ref(true);
const isVisibleB = ref(false);
const animateAndHide = ref(false);
const isLoginTabActive = ref(true);
const isRegisterTabActive = ref(false);
const username = ref("");
const password = ref("");
const email = ref("");
const permission = ref(false);
const error = ref(null);

onMounted(() => {
  if (authStore.isUserLogged) {
    redirectToDashBoard();
  }

  setTimeout(() => {
    animateAndHide.value = true;
  }, 100);

  setTimeout(() => {
    isVisible.value = false;
  }, 5100);

  setTimeout(() => {
    isVisibleB.value = true;

    setTimeout(() => {
      isVisibleB.value = false;
      animateAndHide.value = false;
      isVisible.value = true;
    }, 5100);
  }, 5100);
});

const handleUserSignIn = async () => {
  error.value = null;

  try {
    await authStore.login(username.value, password.value);

    if (authStore.isUserLogged) {
      toast.success("Login realizado com sucesso");
      redirectToDashBoard();
    }
  } catch (err) {
    error.value = "Erro ao tentar fazer login.";
  }

  username.value = "";
  password.value = "";
};

const register = async () => {
  error.value = null;
  if (!username.value || !password.value || !email.value) {
    error.value = "Todos os campos são obrigatórios.";
    return;
  }

  const response = await userService.registerUser(
    username.value,
    email.value,
    password.value
  );
  if (response.status === 201) {
    toast.success(`Você se registrou com sucesso ${username.value}`, {
      timeout: 6000,
    });
    username.value = "";
    password.value = "";
    email.value = "";
    redirectToLogin();
  } else {
    error.value = response.error || "Erro ao registar usuário.";
  }
};

const setActiveTab = (tabName) => {
  if (tabName === "login") {
    username.value = "";
    password.value = "";
    email.value = "";
    permission.value = false;
    isLoginTabActive.value = true;
    isRegisterTabActive.value = false;
  } else if (tabName === "register") {
    username.value = "";
    password.value = "";
    isLoginTabActive.value = false;
    isRegisterTabActive.value = true;
  }
};

const redirectToDashBoard = () => {
  router.push("/dashboard");
};

const redirectToLogin = () => {
  setActiveTab("register");
};
</script>
