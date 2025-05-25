<template>
  <div class="container py-5">
    <h2 class="text-center mb-4">Tickets gerados Recebidas</h2>
    <table class="table table-striped table-bordered">
      <thead class="table-dark">
        <tr>
          <th>REF#</th>
          <th>Assunto</th>
          <th>Status</th>
          <th>Criado em:</th>
          <th>Fechado em:</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(message, index) in ticketList" :key="index">
          <td>{{ message.ticket_id }}</td>
          <td>{{ message.subject }}</td>
          <td>
            {{ message.status }}
          </td>
          <td>{{ formatDate(message.timestamp) }}</td>
          <td>{{ message.finished }}</td>
        </tr>
      </tbody>
    </table>
    <div class="text-center mt-3">
      <button class="btn btn-secondary" @click="redirectToNewTicket">Novo Ticket</button>
    </div>
  </div>
</template>

<script setup>
import ticketService from "@/services/ticketService";
import { ref, onMounted } from "vue";
import { useAuthStore } from "@/stores/authStore";
import { useRouter } from "vue-router";
import { useToast } from "vue-toastification";

const toast = useToast();
const router = useRouter();
const authStore = useAuthStore();

const user = authStore.user;
const ticketList = ref([
  {
    ticket_id: "0",
    subject: "Teste",
    status: "Closed",
    timestamp: "2025-01-09 10:00:00",
    finished: "1 hour",
  },
]);
const filteredTickets = ref([]);

onMounted(async () => {
  try {
    if (!user || !user.token) {
      toast.error("Falha na autenticação!", { timeout: 3000 });
      // Redirect user
    }
    filteredTickets.value = await ticketService.getTickets();
    console.log("Tickets", filteredTickets.value);
    loadData(filteredTickets.value);
  } catch (err) {
    console.log(err);
    toast.error("Ocorreu um erro ao conectar com o servidor!", { timeout: 3000 });
  }
});

const redirectToNewTicket = () => {
  router.push("/user/tickets/new");
};

const loadData = (data = []) => {
  data.forEach((item) => {
    const statusMap = {
      open: "Aberta",
      closed: "Fechada",
      in_progress: "Em andamento",
    };

    const newItem = {
      ticket_id: item.id,
      subject: item.subject,
      status: statusMap[item.status] || "Desconhecido",
      timestamp: item.created_at,
      finished: item.finished_at || "-",
    };

    ticketList.value.push(newItem);
  });
};

const formatDate = (timestamp) => {
  const date = new Date(timestamp);
  return date.toLocaleString("pt-BR", {
    weekday: "short",
    year: "numeric",
    month: "short",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  });
};
</script>
