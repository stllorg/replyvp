<template>
    <div class="container mt-4">
      <!-- Container Superior: Informações do Usuário -->
       <!--Add user.photo-->
      <div class="card shadow mb-4 border-0" style="background-color: #f8f9fa;">
        <div class="card-body text-center">
          <img
            src="https://placehold.co/50x50.png"
            alt="Foto do usuário"
            class="rounded-circle mb-3 shadow"
            style="width: 120px; height: 120px; border: 4px solid #dee2e6;"
          />
          <h5 class="card-title text-secondary fw-bold">{{ user.username }}</h5>
          <p class="card-text text-muted">{{ user.email }}</p>
        </div>
      </div>
  
      <!-- Container Inferior: Lista de Mensagens -->
      <div class="card shadow border-0" style="background-color: #f8f9fa;">
        <div class="card-body">
          <h5 class="card-title text-secondary fw-bold">Mensagens</h5>
          <ul class="list-group list-group-flush">
            <li
              v-for="message in messages"
              :key="message.id"
              class="list-group-item d-flex justify-content-between align-items-center"
              style="background-color: #f8f9fa; border: none;"
            >
              <div>
                <small class="text-muted">{{ formatDateTime(message.dateTime) }}</small>
              </div>
              <div class="text-truncate text-secondary" style="max-width: 70%;">
                {{ truncateMessage(message.content) }}
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        user: JSON.parse(localStorage.getItem('user')) || {
          username: "Guest",
          email: "guest@mail.com"
        },
        messages: [
          { id: 1, dateTime: "2025-01-12T14:30:00", content: "Suspendisse vehicula sapien felis, quis fermentum justo ultrices at. Ut ornare erat nec malesuada aliquet. Sed scelerisque, lorem eget maximus gravida, sem odio ultricies lectus, sit amet tincidunt tortor magna nec ex." },
          { id: 2, dateTime: "2025-01-13T09:15:00", content: "Sed eget accumsan lorem. Morbi lorem justo, dapibus sit amet arcu at, dignissim placerat magna. Fusce faucibus nec enim id convallis. Duis quam est, egestas et dapibus viverra, bibendum non mauris. Sed ipsum purus, sagittis eget nulla vel, accumsan blandit ex. Quisque dui leo, tincidunt eget volutpat a, porta et nulla. Proin convallis tortor tellus, volutpat pellentesque felis porta vitae." },
        ],
      };
    },
    methods: {
      formatDateTime(dateTime) {
        const date = new Date(dateTime);
        return `${date.toLocaleDateString()} ${date.toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" })}`;
      },
      truncateMessage(message) {
        return message.length > 30 ? message.slice(0, 30) + "..." : message;
      },
    },
  };
  </script>
  
  <style>
  .container {
    max-width: 600px;
  }
  .card {
    border-radius: 12px;
  }
  .list-group-item {
    border-bottom: 1px solid #dee2e6;
  }
  .list-group-item:last-child {
    border-bottom: none;
  }
  </style>