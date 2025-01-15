<template>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">IA.ContactCenter</a>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarNav"
          aria-controls="navbarNav"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav mx-auto">
            <li class="nav-item">
              <router-link class="nav-link" to="/">Home</router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/messages">Ver Mensagens</router-link>
            </li>
          </ul>
          <div v-if="isUserLogged" class="d-flex align-items-center" @click="logoutUser">
            Logout
          </div>
          <div v-if="!isUserLogged" class="d-flex align-items-center" @click="navigateToLogin">
            Sign in
          </div>
          <img
            src="https://placehold.co/50x50.png"
            alt="profile"
            class="rounded-circle"
            @click="navigateToLogin"
          />
        </div>
      </div>
    </nav>
</template>
  
<script>
export default {
  name: "AppNavbar",
  data() {
    return {
      isUserLogged: false,
    };
  },
  methods: {
    navigateToLogin() {
      this.$router.push('/login');
    },
    logoutUser() {
      localStorage.removeItem("user");
      this.isUserLogged = false;
      this.$router.push("/");
    },
    // TODO: Usar um serviço para monitorar o estado de login
    checkUserLogged() {
      this.isUserLogged = localStorage.getItem("user") !== null;
    },
  },
    mounted() {
      this.checkUserLogged();
    },
  };
</script>

<style scoped>
img {
  width: 50px;
  height: 50px;
}
</style>