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
          <li v-if="authStore.isUserLogged" class="nav-item">
            <router-link class="nav-link" to="/messages">Abrir Conversa</router-link>
          </li>
          <li v-if="authStore.isUserLogged" class="nav-item">
            <router-link class="nav-link" to="/tickets/history">Meus Tickets</router-link>
          </li>
        </ul>
        <div
          v-if="authStore.isUserLogged"
          class="d-flex align-items-center"
          @click="handleLogout"
        >
          Logout
        </div>
        <div v-else class="d-flex align-items-center" @click="navigateToLogin">
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

<script setup>
import { useAuthStore } from "@/stores/authStore";
import { useRouter } from "vue-router";
const authStore = useAuthStore();
const router = useRouter();

const navigateToLogin = () => {
  router.push("/login");
};

const handleLogout = () => {
  authStore.logout();
  router.push("/");
};
</script>

<style scoped>
img {
  width: 50px;
  height: 50px;
}
</style>
