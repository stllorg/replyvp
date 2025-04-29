<template>
  <div
    class="d-flex vh-100 align-items-center justify-content-center bg-light text-center"
  >
    <div>
      <h1 class="display-1 fw-bold text-danger">404</h1>
      <p class="fs-4">Ops! A página que você procurava não foi encontrada.</p>
      <p>
        <router-link to="/" class="btn btn-primary mt-3"
          >Voltar para a página inicial</router-link
        >
      </p>
    </div>
  </div>
</template>

<script setup>
console.log("Error 404 : Page Not Found");
</script>
