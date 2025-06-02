<template>
  <footer class="footer mt-auto py-3 bg-light">
    <div class="container">
      <div class="d-flex justify-content-between">
        <span class="text-muted">© {{ new Date().getFullYear() }} REPLYVP</span>
        <ul class="list-unstyled d-flex">
          <li class="ms-3">
            <router-link to="/terms">Termos de Uso</router-link>
          </li>
          <li class="ms-3">
            <router-link to="/policy">Política de Privacidade</router-link>
          </li>
          <li class="ms-3">
            <router-link to="/contact">Contato</router-link>
          </li>
        </ul>
      </div>
    </div>
  </footer>
</template>

<style scoped>
.ms-3 a {
  color: #0d6efd;
  text-decoration: none;
}

.ms-3 a:hover {
  text-decoration: underline;
}
</style>
