<template>
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow">
          <div class="card-body p-4">
            <h2 class="card-title text-center mb-4">Pesquisar Ticket</h2>
            <form @submit.prevent="searchTicketHistory()">
              <div class="mb-3">
                <label for="code" class="form-label">Código do Ticket</label>
                <input
                  type="text"
                  id="code"
                  class="form-control"
                  v-model="code"
                  placeholder="Digite o Código do Ticket"
                  required
                />
              </div>
              <div v-if="error" class="alert alert-danger mt-2" role="alert">
                {{ error }}
              </div>
              <div class="d-grid gap-2 mb-3">
                <button type="submit" class="btn btn-primary">Pesquisar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";

const router = useRouter();
const code = ref("");
const error = ref(null);

const searchTicketHistory = () => {
  console.log("Message history");
  router.push({
    name: "TicketHistoryPage",
    query: {
      ticketid: code.value,
    },
  });
};
</script>
