<template>
  <div class="container py-5">
    <h1 class="fs-2 fw-bold text-center text-secondary mb-4">Gerenciar Contas</h1>
    <ManageAccountsBoard :user="user" />
  </div>
</template>

<script setup>
import ManageAccountsBoard from "@/components/ManageAccountsBoard.vue";
</script>