<template>
  <h5 class="card-title text-secondary fw-bold">
    Gere um novo ticket!
    <i
      class="bi bi-pencil-square btn btn-secondary p-2"
      @click="redirectToCreateTicket"
      role="button"
      title="Atualizar Cadastro"
    >
    </i>
  </h5>
</template>
<script setup>
import { useRouter } from "vue-router";
const router = useRouter();

const redirectToCreateTicket = () => {
  router.push("/user/tickets/new");
};
</script>
