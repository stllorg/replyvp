<template>
  <div class="card shadow mb-4 border-0" style="background-color: #f8f9fa">
    <div class="card-body text-center">
      <img src="https://placehold.co/50x50.png" alt="Foto do usuário" class="rounded-circle mb-3 shadow"
        style="width: 120px; height: 120px; border: 4px solid #dee2e6" />
      <h5 class="card-title text-secondary fw-bold">
        {{ user.username }}
        <i class="bi bi-pencil-square btn btn-secondary p-2" @click="redirectToUpdateUserPage" role="button"
          title="Atualizar Cadastro">
        </i>
      </h5>
      <p class="card-text text-muted">{{ user.roles.join(" | ") }}</p>
    </div>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router';

const router = useRouter();

defineProps({
  user: {
    type: Object,
    required: true,
    validator: (value) => {
      return value.username && Array.isArray(value.roles);
    },
  },
});

const redirectToUpdateUserPage = () => {
  router.push("/user/update");
};
</script>