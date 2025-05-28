<template>
  <div class="container mt-4">
    <StaffPanel v-if="showStaffContent" />
    <UserProfileCard v-if="showUserContent" :user="user" />
    <TicketsBoard v-if="showStaffContent" :tickets="pendingTicketsList" @view-messages="goToMessages" @archive-ticket="archiveTicket" />
    <InteractionsBoard v-if="showStaffContent" :interactions="interactionsList" @view-messages="goToMessages" @archive-ticket="archiveTicket" />
    <TicketsBoard v-if="showUserContent" :tickets="userTicketsList" @view-messages="goToMessages" @archive-ticket="archiveTicket" />
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
import UserProfileCard from "@/components/UserProfileCard.vue";
import StaffPanel from "@/components/StaffPanel.vue";

const router = useRouter();
const toast = useToast();
const authStore = useAuthStore();

const user = authStore.user;
const filteredTickets = ref([]);
const userTicketsList = ref([]);
const interactionsList = ref([]);
const pendingTicketsList = ref([]);

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

        const pendingTickets = await ticketService.getPendingTickets();
        loadPendingTickets(pendingTickets);

      } catch (err) {
        console.log(err);
      }
    }
  } catch (err) {
    console.log(err);
    toast.error("Ocorreu um erro ao conectar com o servidor!", { timeout: 3000 });
  }
});

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

const showStaffContent = computed(() =>
  ['admin', 'manager', 'support'].some(role => user.roles.includes(role))
)

const showUserContent = computed(() =>
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

const loadPendingTickets = (data = []) => {
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

    pendingTicketsList.value.push(retrievedUserTicket);
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
