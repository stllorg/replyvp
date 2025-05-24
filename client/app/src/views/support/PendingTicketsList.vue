<template>
  <div class="container py-5">
    <h2 class="text-center mb-4">Tickets Abertos</h2>
    <table class="table table-striped table-bordered">
      <thead class="table-dark">
        <tr>
          <th>Reincidência</th>
          <th>Assunto</th>
          <th>Última mensagem</th>
          <th>Ação</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(message, index) in ticketsList" :key="index">
          <td>{{ message.is_repeat ? "Reincidente" : "Primeiro" }}</td>
          <td>
            {{
              message.subject.slice(0, 20) + (message.subject.length > 20 ? "..." : "")
            }}
          </td>
          <td>{{ formatDate(message.timestamp) }}</td>
          <td>
            <button
              class="btn btn-warning btn-sm"
              @click="assistTicket(message.ticket_id)"
            >
              Dar Assistência
            </button>
          </td>
        </tr>
      </tbody>
    </table>
    <div class="text-center mt-3">
      <button @click="$emit('back-to-contact')" class="btn btn-secondary">Voltar</button>
    </div>
  </div>
</template>

<script setup>
import { useToast } from "vue-toastification";
import supportService from "@/services/supportService";
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "@/stores/authStore";

const authStore = useAuthStore();
const router = useRouter();
const toast = useToast();

const supportUser = authStore.user;

const ticketsList = ref([
  {
    ticket_id: "AB0123",
    is_repeat: false,
    subject: "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
    timestamp: "2025-01-09 10:00:00",
  },
  {
    ticket_id: "AB0124",
    is_repeat: true,
    subject:
      "Vivamus id vulputate ligula. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.",
    timestamp: "2025-01-09 12:00:00",
  },
  {
    ticket_id: "AB0124",
    is_repeat: false,
    subject:
      "Vestibulum eleifend aliquam lacus ut iaculis. Ut pharetra sapien nunc, ullamcorper vestibulum nisi vulputate in.",
    timestamp: "2025-01-09 14:00:00",
  },
]);

onMounted(async () => {
  try {
    if (!supportUser.value || !supportUser.value.token) {
      toast.error("Falha na autenticação!", { timeout: 3000 });
      redirectToLogin();
    } else {
      getTickets(supportUser.value.token);
    }
  } catch (error) {
    console.log(error);
    toast.error("Ocorreu um erro ao conectar com o servidor!", { timeout: 3000 });
  }
});

const redirectToLogin = () => {
  router.push("/login");
};
const redirectToReplyTicket = (ticketId) => {
  console.log(ticketId);
  console.log("Redirecionando para o ticket ", ticketId);
  router.push({
    name: "ReplyTicketPage",
    query: {
      ref: ticketId,
    },
  });
};

const getTickets = async (token) => {
  const response = await supportService.getPendingTickets(token);
  if (response.status === 200) {
    loadPendingTicketsData(response);
  } else {
    toast.error("Ocorreu um erro na resposta servidor!", { timeout: 3000 });
  }
};
const assistTicket = async (ticketId) => {
  try {
    const token = supportUser.value.token;
    const response = await supportService.assistTicket(ticketId, token);
    if (response.status === 200) {
      console.log("Autorização recebida para tratar o ticket escolhido");
      redirectToReplyTicket(ticketId);
    }
  } catch (err) {
    console.log(err);
  }
};
const loadPendingTicketsData = (data = []) => {
  data.forEach((item) => {
    // TODO: ticket_id
    const newItem = {
      ticket_id: 0,
      is_repeat: false,
      subject: item.subject,
      timestamp: item.created_at,
    };

    ticketsList.value.push(newItem);
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
