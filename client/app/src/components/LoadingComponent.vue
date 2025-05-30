<template>
    <div class="loading-animation">
    <p>Loading...</p>
    <div class="spinner"></div>
  </div>
</template>
<script setup>

</script>

<style>
.loading-animation {
  text-align: center;
  font-size: 1.2em;
  color: #555;

.spinner {
  border: 4px solid rgba(0, 0, 0, 0.1);
  border-left-color: #333;
  border-radius: 50%;
  width: 30px;
  height: 30px;
  animation: spin 1s linear infinite;
  margin-top: 10px;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}
}

</style>