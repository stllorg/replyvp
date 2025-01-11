<template>
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow">
          <div class="card-body p-4">
            <h2 class="card-title text-center mb-4">Apagar sua conta em IA.ContactCenter</h2>
            <form @submit.prevent="testDeleteAccount">
              <div class="mb-3">
                <label for="email" class="form-label">Confirmeu seu endereço de email</label>
                <input
                  type="email"
                  id="email"
                  class="form-control"
                  v-model="email"
                  placeholder="Email"
                  required
                />
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Confirme sua senha</label>
                <input
                  type="password"  id="password"
                  class="form-control"
                  v-model="password"
                  placeholder="Senha"
                  required
                />
              </div>
              <div class="mb-3">
                <input
                  type="checkbox"
                  id="confirmation"
                  class="form-check-input"
                  v-model="confirmation"
                  required
                />
                <label class="form-check-label" for="confirmation">
                <a>Confirmo que desejo marcar minha conta para exclusão.</a>
                </label>
              </div>
              <div v-if="error" class="alert alert-danger mt-2" role="alert">
                {{ error }}
              </div>
              <div class="d-grid gap-2 mb-3">
                <button type="submit" class="btn btn-danger">Excluir conta</button>
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
  name: 'TerminateAccount',
  data() {
    return {
      password: "",
      email: "",
      confirmation: false,
      error: null
    };
  },
  methods: {
    testDeleteAccount() {

      if (this.confirmation) {
        this.testScheduleDeletion();
      } else {
        alert(`Operação cancelada! \n Retornando para página inicial!`);
      }
      
    },
    testScheduleDeletion() {
      alert(`A conta de email ${this.email} será apagada em 30 dias!`);
      this.$router.push('/'); 
    }
  },
};
</script>