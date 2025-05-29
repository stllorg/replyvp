<template>
  <div class="d-flex align-items-center justify-content-center">
    <div class="bg-white p-4 p-sm-5 rounded-3 shadow-lg w-100 tray-boundary">
      <div class="row row-cols-1 g-3">
        <div class="col">
          <button class="btn btn-primary d-flex align-items-center justify-content-center w-100 py-3 px-4 shadow-sm" @click="supportArea('pending')">
            <i class="bi bi-ticket-perforated-fill me-2 fs-4"></i>
            <span class="fs-5">Atendimento</span>
          </button>
        </div>
        <div class="col">
          <button class="btn btn-warning d-flex align-items-center justify-content-center w-100 py-3 px-4 shadow-sm" @click="supportArea('search')">
            <i class="bi bi-search me-2 fs-4"></i>
            <span class="fs-5">Pesquisar</span>
          </button>
        </div>
        <div class="col">
          <button 
          class="btn btn-secondary d-flex align-items-center justify-content-center w-100 py-3 px-4 shadow-sm"
          @click="redirectToAccounts()"
          >
            <i class="bi bi-people-fill me-2 fs-4"></i>
            <span class="fs-5">Contas</span>
          </button>
        </div>
        <div class="col">
          <button class="btn btn-secondary d-flex align-items-center justify-content-center w-100 py-3 px-4 shadow-sm"
          @click="redirectToAccounts(true)"
          >
            <i class="bi bi-person-vcard me-2 fs-4"></i>
            <span class="fs-5">Perfil</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { useRouter } from "vue-router";
import { defineEmits } from "vue";
const router = useRouter();

const emit = defineEmits(['display-area']);

const supportArea = (areaName) => {
  emit('display-area', areaName);
};

const redirectToAccounts = (profile = false) => {

  if (profile) {
    console.log("Redirect to edit user profile");
    return;
  }

  router.push("/admin/roles");
}

</script>

<style>
.tray-boundary {
  max-width: 95%;
}

</style>