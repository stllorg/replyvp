<template>
  <div id="app">
    <AppNavbar />
    <router-view />
    <AppFooter />
  </div>
</template>

<script setup>
import AppNavbar from "./components/layout/AppNavbar.vue";
import AppFooter from "./components/layout/AppFooter.vue";
</script>
