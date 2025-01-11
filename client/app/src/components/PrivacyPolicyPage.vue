<template>
    <div class="container mt-5">
      <div class="card shadow-sm">
        <div class="card-body">
          <h1 class="mb-4 text-center">Política de Privacidade</h1>
          <p class="lead">
            Sua privacidade é importante para nós. Esta política de privacidade descreve como coletamos, usamos e protegemos suas informações pessoais quando você usa nosso site.
          </p>
          <hr />
          <div class="mb-4">
            <h2>Informações que coletamos</h2>
            <p>
              Podemos coletar as seguintes informações:
            </p>
            <ul>
              <li>Informações de contato (como nome, endereço de e-mail)</li>
              <li>Dados de uso (como páginas visitadas, tempo gasto no site)</li>
              <li>Informações do dispositivo (como tipo de navegador, endereço IP)</li>
            </ul>
          </div>
  
          <div class="mb-4">
            <h2>Como usamos suas informações</h2>
            <p>
              Usamos suas informações para:
            </p>
            <ul>
              <li>Fornecer e melhorar nossos serviços</li>
              <li>Personalizar sua experiência</li>
              <li>Comunicar com você</li>
              <li>Analisar o uso do site</li>
            </ul>
          </div>
  
          <div class="mb-4">
            <h2>Compartilhamento de informações</h2>
            <p>
              Não compartilhamos suas informações pessoais com terceiros, exceto quando exigido por lei ou quando necessário para fornecer nossos serviços (por exemplo, provedores de hospedagem).
            </p>
          </div>
  
          <div class="mb-4">
            <h2>Segurança de dados</h2>
            <p>
              Implementamos medidas de segurança para proteger suas informações contra acesso não autorizado, uso indevido, alteração ou destruição.
            </p>
          </div>
  
          <div class="mb-4">
            <h2>Seus direitos</h2>
            <p>
              Você tem o direito de acessar, corrigir ou excluir suas informações pessoais. Entre em contato conosco se desejar exercer esses direitos.
            </p>
          </div>

          <div class="mb-4">
          <h2>Exclusão de Conta</h2>
          <p>
            Você tem o direito de solicitar a exclusão da sua conta e dos dados pessoais a ela associados. Para exercer esse direito, clique no link abaixo:
            <router-link class="nav-link" to="/terminate">Solicitar Exclusão da Conta</router-link>.
          </p>
          <p>
            Após a solicitação, sua conta será desativada e seus dados serão excluídos permanentemente em até 30 dias. Durante esse período, você não poderá acessar sua conta.
          </p>
          <p>
            <strong>Informações importantes sobre a exclusão:</strong>
          </p>
          <ul>
              <li>A exclusão da conta é uma ação <strong>irreversível</strong>. Após a conclusão do processo, seus dados não poderão ser recuperados.</li>
              <li>Algumas informações poderão ser retidas por um período determinado, mesmo após a exclusão da conta, para fins legais, fiscais ou de auditoria, conforme exigido pela legislação aplicável. Consulte nossa <router-link to="/policy">Política de Privacidade</router-link> para obter mais detalhes sobre a retenção de dados.</li>
              <li>Se você tiver assinaturas ou serviços ativos vinculados à sua conta, eles serão cancelados. Verifique as condições de cancelamento específicas de cada serviço.</li>
            </ul>
        </div>
  
          <div class="mb-4">
              <h2>Alterações a esta política</h2>
              <p>Podemos atualizar esta política de privacidade periodicamente. Quaisquer alterações serão publicadas nesta página.</p>
          </div>
  
          <div class="text-center mt-4">
            <p>&copy; {{ new Date().getFullYear() }} IA.ContactCenter. Todos os direitos reservados.</p>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
      name: 'PrivacyPolicy',
  };
  </script>
  
  <style scoped>
  @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap');

  body {
      font-family: 'Roboto', sans-serif;
      line-height: 1.6;
  }
  h1, h2 {
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