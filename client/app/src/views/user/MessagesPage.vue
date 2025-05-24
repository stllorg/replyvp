<template>
  <div class="chat-container">
    <div class="chat-box">
      <ChatMessage v-for="(msg, index) in localMessageList" :key="index" :msg="msg" />
    </div>
    <ChatInput @sendMessage="addMessage" />
    <div class="chat-input">
      <input
        v-model="newMessage"
        @keyup.enter="submitNewMessage"
        placeholder="Digite sua mensagem..."
      />
      <button @click="submitNewMessage">Enviar</button>
    </div>
  </div>
</template>

<script setup>
import ticketService from "@/services/ticketService";
import { onMounted, ref } from "vue";
import { useRouter } from "vue-router";
import { useToast } from "vue-toastification";
import { useAuthStore } from "@/stores/authStore";
import ChatMessage from "@/components/Chat/ChatMessage.vue";

const authStore = useAuthStore();
const toast = useToast();
const router = useRouter();

const user = authStore.user;
const localMessageList = ref([
  {
    message_id: 0,
    text: "Oi! Preciso de suporte.",
    sender: "user",
    timestamp: new Date(),
  },
  {
    message_id: 1,
    text: "Olá! Como posso ajudar?",
    sender: "support",
    timestamp: new Date(),
  },
]);

const newMessage = ref(router.query.message || "");
const subject = ref(router.query.subject || "Assunto indefinido");
const ticketId = ref(router.query.ref || 0);
const isTicketOpen = ref(false);

onMounted(checkTicket());
// Get remote messages
const getMessages = async () => {
  // TODO: Use Auth Bearer with token to send user id

  if (!user || !user.token) {
    toast.error("Falha na autenticação!", { timeout: 3000 });
    // Redirect user
  }
  try {
    const messages = await ticketService.getTicketMessages(ticketId, user.id);
    pushMessagesToLocalList(messages);
  } catch (error) {
    toast.error("Ocorreu um erro ao carregar mensagens do ticket!", {
      timeout: 3000,
    });
  }
};
// Get remote messages
const pushMessagesToLocalList = (data = []) => {
  data.forEach((item) => {
    const newItem = {
      message_id: item.message_id,
      text: item.text,
      sender: item.sender,
      timestamp: item.created_at,
    };
    localMessageList.value.push(newItem);
  });
  toast.info("Carregando mensagens", {
    timeout: 3000,
  });
};

const addMessage = (text) => {
  if (text.trim() === "") {
    toast.error("Erro: mensagem em branco!", {
      timeout: 3000,
    });
    return;
  }

  if (isTicketOpen.value) {
    addNewUserMessage(text);
  } else {
    // TODO: Redirect to New Ticket Page
    createNewTicket(text);
  }

  autoReply();
};

const addNewUserMessage = async (text) => {
  try {
    const userMessage = await ticketService.addNewMessage(ticketId.value, user.id, text);

    pushMessagesToLocalList([userMessage]);
    autoReply();
  } catch (err) {
    console.log(err);
    toast.error("Ocorreu um erro ao enviar mensagem para o servidor!", {
      timeout: 3000,
    });
  }
};

const autoReply = () => {
  localMessageList.value.push({
    message_id: localMessageList.value.length + 1,
    text: "Estamos verificando sua solicitação.",
    sender: "none",
    timestamp: new Date(),
  });
};

const createNewTicket = async (text) => {
  try {
    const createdTicketId = await ticketService.createTicket(
      subject.value,
      text
    );
    redirectToCreatedTicket(createdTicketId);
  } catch (err) {
    console.log(err);
    toast.error("Ocorreu um erro ao conectar com o servidor!", {
      timeout: 3000,
    });
  }
};

const redirectToCreatedTicket = (newTicketId) => {
  console.log("Current Ticket is ID: ", newTicketId);

  router.push({
    name: "MessagesPage",
    query: {
      ref: newTicketId,
    },
  });
};

const checkTicket = () => {
  if (ticketId.value != 0) {
    toast.info(`Ticket REF#${ticketId.value}`, { timeout: 3000 });
    isTicketOpen.value = true;
    getMessages();
  } else {
    // TODO: Redirect to New Ticket Page
    toast.info(`Envie sua mensagem para abrir um novo ticket`, {
      timeout: 4000,
    });
  }
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
  background: #fff;
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
</style>
