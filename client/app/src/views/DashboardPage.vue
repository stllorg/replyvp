<template>
  <div class="container mt-4">
    <LoadingComponent v-if="loading" />
    <GenerateTicketComponent v-if="showUserContent" />
    <TicketsBoard
      v-if="showUserContent"
      :boardTitle="boardsTitles.userTickets"
      :tickets="userTicketsList"
      @view-messages="goToMessages"
      @archive-ticket="archiveTicket"
    />
  </div>
</template>

<script setup>
import ticketService from "@/services/ticketService";
import { useToast } from "vue-toastification";
import { useRouter } from "vue-router";
import { ref, onMounted } from "vue";
import { useAuthStore } from "@/stores/authStore";
import TicketsBoard from "@/components/TicketsBoard.vue";
import LoadingComponent from "@/components/LoadingComponent.vue";
import GenerateTicketComponent from "@/components/GenerateTicketComponent.vue";

const router = useRouter();
const authStore = useAuthStore();
const toast = useToast();

const showUserContent = ref(false);
const userTicketsList = ref([]);
const displayArea = ref(null);
const loading = ref(false);
const boardsTitles = {
  userTickets: "Meus tickets",
};

onMounted(async () => {
  try {
    if (!authStore.isUserLogged) {
      toast.error("Falha na autenticação!", { timeout: 3000 });
      navigateToHome();
      return;
    }

    if (authStore.isRegularUser) {

      showUserContent.value = true;
      handleDisplayArea("userArea");
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

const handleDisplayArea = async (areaName) => {
  loading.value = true;
  displayArea.value = null;

  if (areaName === "userArea") {
    try {
      const response = await ticketService.getTickets();
      if (response.status != 200) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }

      loadUserData(response.data);
      displayArea.value = "userArea";
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
