<template>
  <div class="col-12">
    <div class="card mb-2 main-card">
      <div class="card-body col-8 main-concept">
        <h5 class="card-title">Precisa de ajuda?</h5>
        <p class="card-text">
          Estamos prontos para analisar seu caso e resolver seu problema.
        </p>
        <button type="button" class="btn btn-light">Abrir Ticket</button>
      </div>
      <div class="col-4">
        <img
        src="/images/freepik-customer-support-flat-design-illustration.png"
        class="illustration"
        alt="Illustration - Customer Support ( Flat design)"
        />
      </div>
    </div>
  </div>
</template>

<style scoped>
.main-card {
  background-color: deepskyblue;
  min-height: 60vh;
}

.illustration {
  position: absolute;
  top: 40%;
  left: 65%;
  transform: translate(-50%, -50%);
  z-index: 10;
  max-width: 60%;
  max-height: 90%;
}

.main-concept {
  color: black; /* Or any color you prefer for the text */
  padding: 20px;
  position: absolute;
  top: 25%;
  left: 5%;
  border-radius: 10px; /* Optional: Add rounded corners */
  text-align: left;
  z-index: 20;
}
</style>

<script setup>
console.log("Home Page");
</script>
