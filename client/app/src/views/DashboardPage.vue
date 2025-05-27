<template>
  <div class="container mt-4">
    <div class="card shadow mb-4 border-0" style="background-color: #f8f9fa">
      <div class="card-body text-center">
        <img src="https://placehold.co/50x50.png" alt="Foto do usuário" class="rounded-circle mb-3 shadow"
          style="width: 120px; height: 120px; border: 4px solid #dee2e6" />
        <h5 class="card-title text-secondary fw-bold">
          {{ user.username }}
          <i class="bi bi-pencil-square btn btn-secondary p-2" @click="redirectToUpdateUserPage" role="button"
            title="Atualizar Cadastro">
          </i>
        </h5>
        <p class="card-text text-muted">{{ user.roles.join(" | ") }}</p>
      </div>
    </div>
    <InteractionsBoard v-if="showInteractions" :interactions="interactionsList" @view-messages="goToMessages" @archive-ticket="archiveTicket" />
    <TicketsBoard v-if="showTicketsBoard" :interactions="userTicketsList" @view-messages="goToMessages" @archive-ticket="archiveTicket" />
  </div>
</template>

<script setup>
import ticketService from "@/services/ticketService";
import { useToast } from "vue-toastification";
import { useRouter } from "vue-router";
import { ref, onMounted } from "vue";
import { useAuthStore } from "@/stores/authStore";
import { computed } from 'vue';
import InteractionsBoard from '@/components/InteractionsBoard.vue';
import TicketsBoard from "@/components/TicketsBoard.vue";

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
      }
    } else if (user.roles.includes("support") || user.roles.includes("manager") || user.roles.includes("admin")) {
      try {
        const response = await ticketService.getTicketsWithUserMessages();
        loadInteractionsData(response.data);
      } catch (err) {
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

const archiveTicket = (id) => {
  console.log("No logic to archive ticket:", id);
};

const showInteractions = computed(() =>
  ['admin', 'manager', 'support'].some(role => user.roles.includes(role))
)

const showTicketsBoard = computed(() =>
  ['user'].some(role => user.roles.includes(role))
)

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
