<template>
    <div class="container py-5">
      <div class="row justify-content-center">
        <div class="col-md-6">
          <div class="card shadow">
            <div class="card-body p-4">
              <h2 class="card-title text-center mb-4">Buscar Mensagens Anteriores</h2>
              <form @submit.prevent="searchMessageHistory()">
                <div class="mb-3">
                  <label for="code" class="form-label">Código da Mensagem</label>
                  <input
                    type="text"
                    id="code"
                    class="form-control"
                    v-model="code"
                    placeholder="Digite o código da mensagem"
                    required
                  />
                </div>
                <div v-if="error" class="alert alert-danger mt-2" role="alert">
                  {{ error }}
                </div>
                <div class="d-grid gap-2 mb-3">
                  <button type="submit" class="btn btn-primary">Buscar</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  
  export default {
    name: 'MessageHistoryPage',
    data() {
    },
    mounted() {
    },
    methods: {
        searchMessageHistory() {
            console.log("Message history");
        }
    },
  };
  </script>