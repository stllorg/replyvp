<template>
  <div class="container mt-4">
    <div class="card shadow mb-4 border-0" style="background-color: #f8f9fa">
      <div class="card-body text-center">
        <div class="d-inline-flex">
          <h5 class="card-title text-secondary fw-bold">
          Ticket #{{ ticketRef.id }}
          </h5>
          <div>
            <div class="d-inline-flex">
              <p class="mx-2">{{ ticketRef.status.toUpperCase() }}</p>
              <p 
                :class="`${ticketRef.status}`"
                class="p-3 mb-4 rounded-circle fw-bold d-inline-block">
              </p>
            </div>
          </div>
        </div>
        <div class="time-market">
          <p class="card-text text-muted">Data de criação: {{ ticketRef.createdAt }}</p>
        </div>
        <div class="ticket-description d-flex">
          Descrição: <p class="card-text text-muted text-md-start">{{ ticketRef.subject }}</p>
        </div>
      </div>
    </div>
  </div>
  <div v-for="(msg, index) in localMessageList" :key="index" :msg="msg" class="container mt-4 "
        :class="`${msg.sender}-card`">
    <div class="card shadow mb-4 border-0" style="background-color: #f8f9fa">
      <div class="card-body text-center">
        <div class="d-inline-flex">
          <h5 class="card-title text-secondary fw-bold text-left">
          User #{{ msg.id }}
          </h5>
        </div>
        <div class="ticket-description d-flex">
          Descrição: <p class="card-text text-muted text-md-start">{{ msg.content }}</p>
        </div>
      </div>
    </div>
  </div>
  <div class="chat-container">
    <div class="chat-box">
      <div v-for="(msg, index) in localMessageList" :key="index" :msg="msg" class="message"
        :class="`${msg.sender}-message`">
        <p>{{ msg.content }}</p>
        <small>{{ formatMessageTime(msg.createdAt) }}</small>
      </div>
    </div>
    <div class="chat-input">
      <input v-model="textInput" @keyup.enter="submitMessage" placeholder="Digite sua mensagem..." />
      <button @click="submitMessage">Enviar</button>
    </div>
  </div>
</template>

<script setup>
import ticketService from "@/services/ticketService";
import { onMounted, ref } from "vue";
import { useRouter, useRoute } from "vue-router";
import { useToast } from "vue-toastification";
import { useAuthStore } from "@/stores/authStore";
import { formatMessageTime } from "@/utils/dateUtils";


const authStore = useAuthStore();
const toast = useToast();
const router = useRouter();
const route = useRoute();

const user = authStore.user;
const localMessageList = ref([]);

const textInput = ref("-");
const isTicketOpen = ref(false);

const ticketRef = ref(
  {
    id: 0,
    subject: "None",
    createdAt: new Date().toISOString,
    status: 'open',
    userId: 0
  }
);

onMounted(
  () => {
    if (!user || !user.token) {
      toast.error("Faça login para continuar!", { timeout: 200 });
      router.push("/login");
    }

    if (route.query && route.query.ticketId != 0) {
      isTicketOpen.value = true;
      fetchTicketData();
      fetchMessages();
    }
  }
);

const fetchTicketData = async () => {
  try {
    const ticket = await ticketService.getTicketById(route.query.ticketId);

    if (ticket && ticket != null) {
      ticketRef.value.id = ticket.id;
      ticketRef.value.subject = ticket.subject;
      ticketRef.value.userId = ticket.userId;
      ticketRef.value.createdAt = ticket.createdAt;
      ticketRef.value.status = "open";
    } 
  } catch (err) {
    console.log(err);
  }
};

const fetchMessages = async () => {
  try {
    const messages = await ticketService.getTicketMessages(route.query.ticketId);

    if (messages && messages != null) {
      pushMessagesToLocalList(messages);
    } else {
      loadDefaultMessages();
    }
  } catch (error) {
    toast.error("Ocorreu um erro ao carregar mensagens do ticket!", {
      timeout: 3000,
    });
  }
};

const loadDefaultMessages = () => {
  const defaultMessages = [
    {
      id: 0,
      content: "Oi! Preciso de suporte.",
      sender: "user",
      createdAt: new Date(),
    },
    {
      id: 1,
      content: "Olá! Como posso ajudar?",
      sender: "support",
      createdAt: new Date(),
    },
  ];

  localMessageList.value.push(...defaultMessages);
}

const pushMessagesToLocalList = (data = []) => {
  if (!data) return;

  data.forEach((item) => {
    let senderRole = () => {
      if (item.roles.includes('user')) {
        return "user";
      } else if (item.roles.includes('admin')) {
        return "admin";
      } else if (item.roles.includes('manager')) {
        return "manager";
      } else if (item.roles.includes('support')) {
        return "support";
      }
    };

    const newItem = {
      id: item.messageId ?? localMessageList.value.length + 1,
      content: item.content,
      sender: senderRole(),
      createdAt: item.createdAt === null || item.createdAt === "null" ? new Date().toISOString() : item.createdAt,
      roles: item.roles,
    };

    localMessageList.value.push(newItem);
  });
  toast.info("Carregando mensagens", {
    timeout: 3000,
  });
};

const submitMessage = async () => {
  if (!isTicketOpen.value) return;

  if (textInput.value.trim() === "") {
    toast.error("Erro: mensagem em branco!", { timeout: 3000, });

    return;
  }

  try {
    const userMessage = await ticketService.addNewMessage(route.query.ticketId, textInput.value);
    textInput.value = "";
    pushMessagesToLocalList([userMessage]);
    if (userMessage.roles) {
      if (!userMessage.roles.includes('user')) {
        autoReply();
      }
    }
  } catch (err) {
    console.log(err);
    toast.error("Ocorreu um erro ao enviar mensagem para o servidor!", {
      timeout: 3000,
    });
  }
};

const autoReply = () => {
  let replyText = "Estamos verificando sua solicitação.";
  localMessageList.value.push({ content: replyText });
};

</script>

<style scoped>
.chat-container {
  width: 400px;
  max-width: 100%;
  margin: auto;
  display: flex;
  flex-direction: column;
  border: 1px solid #ccc;
  border-radius: 10px;
  background: #f9f9f9;
}

.chat-box {
  padding: 10px;
  max-height: 400px;
  overflow-y: auto;
  display: flex;
  flex-direction: column;
}

.message {
  padding: 8px 12px;
  border-radius: 10px;
  margin: 5px 0;
  max-width: 70%;
  display: inline-block;
  line-break: anywhere;
}

.user-message {
  background: #dcf8c6;
  align-self: flex-end;
  text-align: right;
  margin-left: auto;
}

.support-message {
  background: #d6f4ff;
  align-self: flex-start;
  text-align: left;
  margin-right: auto;
}

.manager-message {
  background: #89d5f1;
  align-self: flex-start;
  text-align: left;
  margin-right: auto;
}

.admin-message {
  background: #34c8ff;
  align-self: flex-start;
  text-align: left;
  margin-right: auto;
}

.chat-input {
  display: flex;
  padding: 10px;
  border-top: 1px solid #ccc;
}

.chat-input input {
  flex-grow: 1;
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 5px;
}

.chat-input button {
  margin-left: 10px;
  padding: 8px 15px;
  border: none;
  background: #007bff;
  color: white;
  border-radius: 5px;
  cursor: pointer;
}

.open {
  background-color: #00a305;
}

.in_progress {
  background-color: #fffb00;
  color: #333;
}

.closed {
  background-color: #ee190a;
}
</style>
