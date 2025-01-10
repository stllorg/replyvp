<template>
  <div class="container mt-5">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <img src="https://placehold.co/512x256.png" class="card-img-top" alt="Imagem de Missão">
          <div class="card-body">
            <h5 class="card-title">Missão da Empresa</h5>
            <p class="card-text">Nossa missão é ser provedor de atendimento com IA para empresas parceiras.</p>
          </div>
        </div>
      </div>
      <div class="col-12">
        <div class="card">
          <img src="https://placehold.co/512x256.png" class="card-img-top" alt="Imagem de Missão">
          <div class="card-body">
            <h5 class="card-title">Vantagens de contratar</h5>
            <p class="card-text">Aumente vendas, melhore o suporte e conquiste mais clientes com nossos serviços.</p>
          </div>
        </div>
      </div>
      <div class="col-12">
        <div class="card">
          <img src="https://placehold.co/512x256.png" class="card-img-top" alt="Imagem de Missão">
          <div class="card-body">
            <h5 class="card-title">Planos e Preços</h5>
            <p class="card-text">Entre em contato e transforme seu negócio com nossos serviços.</p>
            <router-link class="btn btn-primary" to="/contact">Saiba mais</router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "AppHome",
};
</script>