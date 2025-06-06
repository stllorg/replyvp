<template>
  <div class="container mt-4">
    <StaffPanel v-if="displayArea === 'menu' && showStaffContent" @display-area="handleDisplayArea" />
    <LoadingComponent v-if="loading" />
    <WorkBoard
      v-if="displayArea === 'pending' && showStaffContent"
      :boardTitle="boardsTitles.pendingTickets"
      :tickets="pendingTicketsList"
      @view-messages="goToMessages"
      @archive-ticket="archiveTicket"
    />
    <InteractionsBoard
      v-if="displayArea === 'search' && showStaffContent"
      :boardTitle="boardsTitles.interactions"
      :interactions="interactionsList"
      @view-messages="goToMessages"
      @archive-ticket="archiveTicket"
    />
  </div>
</template>

<script setup>
import ticketService from "@/services/ticketService";
import { getTicketsWithUserMessages } from "@/services/userService";
import { useToast } from "vue-toastification";
import { useRouter } from "vue-router";
import { ref, onMounted } from "vue";
import { useAuthStore } from "@/stores/authStore";
import { computed } from "vue";
import InteractionsBoard from "@/components/InteractionsBoard.vue";
import WorkBoard from "@/components/WorkBoard.vue";
import StaffPanel from "@/components/StaffPanel.vue";
import LoadingComponent from "@/components/LoadingComponent.vue";

const router = useRouter();
const authStore = useAuthStore();
const toast = useToast();

const user = authStore.user;
const interactionsList = ref([]);
const pendingTicketsList = ref([]);

const displayArea = ref('menu');
const loading = ref(false);
const boardsTitles = {
  pendingTickets: "Tickets de casos abertos",
  interactions: "Histórico de Interações",
};

onMounted(async () => {
  try {
    if (!authStore.isUserLogged) {
      toast.error("Falha na autenticação!", { timeout: 3000 });
      navigateToHome();
    }

  } catch (err) {
    console.log(err);
    toast.error("Ocorreu um erro ao conectar com o servidor!", {
      timeout: 3000,
    });
  }
});

const navigateToHome = () => {
  router.push("/");
};

const goToMessages = (id) => {
  router.push({
    name: "MessagesPage",
    query: {
      ticketId: id,
    },
  });
};

const archiveTicket = (id) => {
  console.log("No logic to archive ticket:", id);
};

const showStaffContent = computed(() =>
  ["admin", "manager", "support"].some((role) => user.roles.includes(role))
);

const handleDisplayArea = async (areaName) => {
  loading.value = true;
  displayArea.value = null;

  if (areaName === "pending") {
    try {
      const response = await ticketService.getPendingTickets();
      if (response.status != 200) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }

      loadPendingTickets(response.data);
      displayArea.value = "pending";
    } catch (err) {
      console.log("Failed to fetch data:", err);
    } finally {
      loading.value = false;
    }
  } else if (areaName === "search") {
    try {
      const response = await getTicketsWithUserMessages();

      if (response.status != 200 && response.status != 404) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      loadInteractionsData(response.data);
      displayArea.value = "search";
    } catch (err) {
      console.log("Failed to fetch data:", err);
    } finally {
      loading.value = false;
    }
  } else {
    setTimeout(() => {
      displayArea.value = areaName;
      loading.value = false;
    }, 800);
  }
};

const loadPendingTickets = (data = []) => {
  data.forEach((item) => {
    const retrievedUserTicket = {
      id: item.id,
      subject: item.subject,
      status: item.status,
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
