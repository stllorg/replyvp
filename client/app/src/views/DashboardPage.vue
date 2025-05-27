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
      v-if="user.roles.includes('admin') || user.roles.includes('manager') || user.roles.includes('support')"
      class="card shadow border-0"
      style="background-color: #f8f9fa"
    >
      <div class="card-body">
        <h5 class="card-title text-secondary fw-bold">Interações em tickets</h5>
        <ul v-if="interactionsList.length === 0" class="list-group list-group-flush">
              <p class="text-center text-truncate text-secondary">Sem registros de interações</p>
        </ul>
        <ul v-if="interactionsList.length != 0" class="list-group list-group-flush">
          <li v-for="ticket in interactionsList"
            :key="ticket.id"
            class="list-group-item d-flex justify-content-between align-items-center"
            style="background-color: #f8f9fa; border: none"
          >
            <div>
              <small class="text-muted">{{ formatFullDateTime(ticket.createdAt) }}</small>
            </div>
            <div
              class="text-truncate text-secondary text-decoration-underline"
              style="max-width: 70%"
            >
              <router-link v-if="ticket.id != 0"
                :to="{
                  name: 'MessagesPage',
                  query: {
                    ticketId: ticket.id,
                  },
                }"
              class="text-truncate text-secondary subject-link">{{ truncateText(ticket.subject) }}</router-link>
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
              <small class="text-muted">{{ formatFullDateTime(ticket.createdAt) }}</small>
            </div>
            <div
              class="text-truncate text-secondary text-decoration-underline"
              style="max-width: 70%"
            >
              <router-link v-if="ticket.id != 0"
                :to="{
                  name: 'MessagesPage',
                  query: {
                    ticketId: ticket.id,
                  },
                }"
              class="text-truncate text-secondary subject-link">{{ truncateText(ticket.subject) }}</router-link>
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
import { formatFullDateTime } from "@/utils/dateUtils";


const router = useRouter();
const toast = useToast();
const authStore = useAuthStore();

const user = authStore.user;
const filteredTickets = ref([]);
const userTicketsList = ref([]);
const interactionsList = ref([]);

onMounted(async () => {
  try {
    if (!user || !user.token) {
      toast.error("Falha na autenticação!", { timeout: 3000 });
      // Redirect user
    }

    if (user.roles.includes("user")) {
      try {
        filteredTickets.value = await ticketService.getTickets();
        loadUserData(filteredTickets.value);
      } catch (err) {
        console.log("Falha ao obter lista de tickets...");
        console.log(err);
        
        let sampleArray = ref([[
          {
            id: 0,
            createdAt: new Date().toISOString(),
            subject:
            "Suspendisse vehicula sapien felis, quis fermentum justo ultrices at. Ut ornare erat nec malesuada aliquet. Sed scelerisque, lorem eget maximus gravida, sem odio ultricies lectus, sit amet tincidunt tortor magna nec ex.",
            status: "Aberta",
          },
        ]]);
        loadUserData(sampleArray);
      }
    } else if (user.roles.includes("support") || user.roles.includes("manager") || user.roles.includes("admin") ) {
      try {
        const response = await ticketService.getTicketsWithUserMessages();
        loadInteractionsData(response.data);
      } catch (err) {
         console.log("Falha ao obter lista de interações...");
         console.log(err);
        }
    }
  } catch (err) {
    console.log(err);
    toast.error("Ocorreu um erro ao conectar com o servidor!", { timeout: 3000 });
  }
});

const redirectToUpdateUserPage = () => {
  router.push("/user/update");
};

const goToMessages = (id) => {
  router.push({
    name: 'MessagesPage', 
    query: {
      ticketId: id,
    },
  });
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
      createdAt: item.createdAt,
    };

    userTicketsList.value.push(retrievedUserTicket);
  });
};

const loadInteractionsData = (data = []) => {
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
      createdAt: item.createdAt ?? new Date().toISOString(),
    };

    interactionsList.value.push(retrievedUserTicket);
  });
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
.subject-link {
  max-width: 70%;
  cursor: pointer;
  text-decoration: none;
}
</style>
