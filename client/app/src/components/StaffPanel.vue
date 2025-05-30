<template>
  <div class="card shadow mb-4 border-0" style="background-color: #f8f9fa">
    <h1 class="fs-2 fs-sm-3 fw-bold text-center text-secondary mb-4">
      Staff Area
    </h1>
    <StaffUtilsTray @display-area="emitDisplayArea" />
    <br />
  </div>
</template>

<script setup>
import { defineEmits } from "vue";
import StaffUtilsTray from "./StaffUtilsTray.vue";

const emit = defineEmits(["display-area"]);

const emitDisplayArea = (areaName) => {
  emit("display-area", areaName);
};
</script>
