<template>
  <div class="container mt-5">
    <div class="card shadow-sm">
      <div class="card-body">
        <h1 class="mb-4 text-center">Termos de Serviço</h1>
        <p class="lead">
          Bem-vindo ao nosso site! Ao acessar ou usar nosso site, você concorda em
          obedecer a estes Termos de Serviço. Leia-os atentamente antes de usar nossos
          serviços.
        </p>
        <hr />
        <div class="mb-4">
          <h2>1. Aceitação dos Termos</h2>
          <p>
            Ao acessar ou usar este site, você concorda em ficar vinculado por estes
            Termos de Serviço, todas as leis e regulamentos aplicáveis, e concorda que é
            responsável pelo cumprimento de quaisquer leis locais aplicáveis. Se você não
            concordar com algum destes termos, está proibido de usar ou acessar este site.
          </p>
        </div>

        <div class="mb-4">
          <h2>2. Uso do Site</h2>
          <p>
            Você concorda em usar este site apenas para fins legais e de uma forma que não
            infrinja os direitos de, ou restrinja ou iniba o uso e o aproveitamento deste
            site por qualquer outra pessoa. O comportamento proibido inclui assediar ou
            causar angústia ou inconveniente a qualquer outra pessoa, transmitir conteúdo
            obsceno ou ofensivo ou interromper o fluxo normal de diálogo dentro deste
            site.
          </p>
        </div>

        <div class="mb-4">
          <h2>3. Propriedade Intelectual</h2>
          <p>
            Todo o conteúdo incluído neste site, como texto, gráficos, logotipos, imagens,
            clipes de áudio, downloads digitais e compilações de dados, é propriedade de
            [Nome da sua empresa] ou de seus fornecedores de conteúdo e protegido por leis
            de direitos autorais internacionais.
          </p>
        </div>

        <div class="mb-4">
          <h2>4. Limitação de Responsabilidade</h2>
          <p>
            Em nenhuma circunstância [Nome da sua empresa] será responsável por quaisquer
            danos (incluindo, sem limitação, danos por perda de dados ou lucro, ou devido
            a interrupção de negócios) decorrentes do uso ou da incapacidade de usar os
            materiais neste site, mesmo que [Nome da sua empresa] ou um representante
            autorizado da [Nome da sua empresa] tenha sido notificado oralmente ou por
            escrito da possibilidade de tais danos.
          </p>
        </div>

        <div class="mb-4">
          <h2>5. Alterações nos Termos de Serviço</h2>
          <p>
            Podemos revisar estes Termos de Serviço a qualquer momento, sem aviso prévio.
            Ao usar este site, você concorda em ficar vinculado à versão atual desses
            Termos de Serviço.
          </p>
        </div>

        <div class="mb-4">
          <h2>6. Lei Aplicável</h2>
          <p>
            Estes Termos de Serviço serão regidos e interpretados de acordo com as leis de
            [Seu Estado/País], e você se submete irrevogavelmente à jurisdição exclusiva
            dos tribunais desse Estado ou localidade.
          </p>
        </div>

        <div class="text-center mt-4">
          <p>
            &copy; {{ new Date().getFullYear() }} Sua Empresa. Todos os direitos
            reservados.
          </p>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
console.log("Terms of Service");
</script>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap");

body {
  font-family: "Roboto", sans-serif;
  line-height: 1.6;
}
h1,
h2 {
  font-weight: 500;
}
.card {
  border: none;
}
a {
  color: #1a0dab;
  text-decoration: none;
}

a:hover {
  text-decoration: underline;
}
</style>
