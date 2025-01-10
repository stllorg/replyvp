<template>
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="contact-form bg-white p-4 rounded shadow">
          <h2 class="text-center mb-4">Envie sua mensagem</h2>
          <form @submit.prevent="handleSubmit">
            <div class="mb-3">
              <label for="username" class="form-label">Usuário</label>
              <input
                type="text"
                id="username"
                class="form-control"
                v-model="username"
                placeholder="Digite o seu nome de usuário"
                required
              />
            </div>

            <div class="mb-3">
              <label for="subject" class="form-label">Assunto</label>
              <input
                type="text"
                id="subject"
                class="form-control"
                v-model="subject"
                placeholder="Assunto da mensagem"
                required
              />
            </div>

            <div class="mb-3">
              <label for="message" class="form-label">Mensagem</label>
              <textarea
                id="message"
                class="form-control"
                v-model="message"
                rows="5"
                placeholder="Escreva a sua mensagem"
                required
              ></textarea>
            </div>

            <div class="text-center">
              <button type="submit" class="btn btn-primary px-4">Enviar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      username: "",
      subject: "",
      message: "",
    };
  },
  methods: {
    handleSubmit() {
      alert(`Mensagem enviada por: ${this.username}\nMensagem: ${this.message}`);
      this.subject = "";
      this.message = "";
    },
  },
};
</script>

<style scoped>
body {
  background: linear-gradient(120deg, #fdfbfb 0%, #ebedee 100%);
  font-family: 'Arial', sans-serif;
}
.contact-form {
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
}
</style>
