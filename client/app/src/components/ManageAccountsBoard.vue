<template>
  <div class="card shadow mb-4 border-0" style="background-color: #f8f9fa">
    <div class="card-body text-center">
      <img src="https://placehold.co/50x50.png" alt="Foto do usuário" class="rounded-circle mb-3 shadow"
        style="width: 120px; height: 120px; border: 4px solid #dee2e6" />
      <h5 class="card-title text-secondary fw-bold">
        Gerenciar Contas
      </h5>
      Busque usuários e defina funções
    </div>
    <AccountsBoard />
  </div>
</template>

<script setup>
import AccountsBoard from "@/components/AccountsBoard.vue";
</script>