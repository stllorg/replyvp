// TODO: Add Button to Edit User
<template>
  <div class="container mt-4">
    <div class="card shadow mb-4 border-0" style="background-color: #f8f9fa">
      <div class="card-body text-center">
        <img
          src="https://placehold.co/50x50.png"
          alt="Foto do usuário"
          class="rounded-circle mb-3 shadow"
          style="width: 120px; height: 120px; border: 4px solid #dee2e6"
        />
        <h5 class="card-title text-secondary fw-bold">
          {{ user.username }}
          <i
            class="bi bi-pencil-square btn btn-secondary p-2"
            @click="redirectToUpdateUserPage"
            role="button"
            title="Atualizar Cadastro"
          >
          </i>
        </h5>
        <p class="card-text text-muted">{{ user.roles.join(" | ") }}</p>
      </div>
    </div>

    <div
      v-if="user.roles.includes('user')"
      class="card shadow border-0"
      style="background-color: #f8f9fa"
    >
      <div class="card-body">
        <h5 class="card-title text-secondary fw-bold">Atendimentos do Usuário</h5>
        <ul class="list-group list-group-flush">
          <li
            v-for="ticket in userTicketsList"
            :key="ticket.id"
            class="list-group-item d-flex justify-content-between align-items-center"
            style="background-color: #f8f9fa; border: none"
          >
            <div>
              <small class="text-muted">{{ formatDateTime(ticket.timestamp) }}</small>
            </div>
            <div
              class="text-truncate text-secondary text-decoration-underline"
              style="max-width: 70%"
            >
              <router-link
                :to="{
                  name: user.roles.includes('user') ? 'MessagesPage' : 'ReplyTicketPage',
                  query: {
                    ref: ticket.id,
                  },
                }"
                class="text-truncate text-secondary"
                style="max-width: 70%; cursor: pointer; text-decoration: none"
              >
                {{ truncateText(ticket.subject) }}
              </router-link>
            </div>
            <div class="d-flex">
              <button
                class="btn btn-outline-primary btn-sm me-2"
                @click="goToMessages(ticket.id)"
                title="Ver Mensagens"
              >
                <i class="bi bi-chat-dots"></i>
              </button>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script setup>
import ticketService from "@/services/ticketService";
import { useToast } from "vue-toastification";
import { useRouter } from "vue-router";
import { ref, onMounted } from "vue";
import { useAuthStore } from "@/stores/authStore";

const router = useRouter();
const toast = useToast();
const authStore = useAuthStore();

const user = authStore.user;
const filteredTickets = ref([]);
const userTicketsList = ref([
  {
    id: 0,
    timestamp: "2025-01-12T14:30:00",
    subject:
      "Suspendisse vehicula sapien felis, quis fermentum justo ultrices at. Ut ornare erat nec malesuada aliquet. Sed scelerisque, lorem eget maximus gravida, sem odio ultricies lectus, sit amet tincidunt tortor magna nec ex.",
    status: "Aberta",
  },
]);

onMounted(async () => {
  try {
    // TODO: Use Auth Bearer with token to send user id
    if (!user || !user.token) {
      toast.error("Falha na autenticação!", { timeout: 3000 });
      // Redirect user
    }

    if (user.roles.includes("user")) {
      filteredTickets.value = await ticketService.getTickets(user.id);
      loadUserData(filteredTickets.value);
    } else {
      console.log("Load pending tickets");
    }
  } catch (err) {
    console.log(err);
    toast.error("Ocorreu um erro ao conectar com o servidor!", { timeout: 3000 });
  }
});

const redirectToUpdateUserPage = () => {
  router.push("/user/update");
};

const goToMessages = (ticketId) => {
  console.log(ticketId);
};

const loadUserData = (data = []) => {
  data.forEach((item) => {
    const statusMap = {
      open: "Aberta",
      closed: "Fechada",
      in_progress: "Em andamento",
    };

    const retrievedUserTicket = {
      id: item.id,
      subject: item.subject,
      status: statusMap[item.status] || "Desconhecido",
      timestamp: item.created_at,
    };

    userTicketsList.value.push(retrievedUserTicket);
  });
};

const formatDateTime = (dateTime) => {
  const date = new Date(dateTime);
  return `${date.toLocaleDateString()} ${date.toLocaleTimeString([], {
    hour: "2-digit",
    minute: "2-digit",
  })}`;
};

const truncateText = (text) => {
  return text.length > 30 ? text.slice(0, 30) + "..." : text;
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
