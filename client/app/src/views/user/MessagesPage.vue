<template>
  <div class="chat-container">
    <div class="chat-box">

      <div v-for="(msg, index) in localMessageList" :key="index" :msg="msg"
      class="message" :class="msg.sender === 'user' ? 'user-message' : 'support-message'">
      <p>{{ msg.text }}</p>
      <small>{{ formatMessageTime(msg.timestamp) }}</small>
      </div>


    </div>
    <div class="chat-input">
      <input
        v-model="textInput"
        @keyup.enter="submitMessage"
        placeholder="Digite sua mensagem..."
      />
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

const textInput = ref("-");
const subject = ref("Assunto indefinido");
const loadedTicketRef = ref(route.query.ticketId || 0);
const isTicketOpen = ref(false);

onMounted(
() => {
  if (route.query && route.query.ticketId) {
    checkTicket();
  } else {
  console.log(`Ref: ${loadedTicketRef.value}`);
    checkTicket()}

}
);
// Get remote messages
const fetchMessages = async () => {

  if (!user || !user.token) {
    toast.error("Falha na autenticação!", { timeout: 3000 });
    // Redirect user
  }
  try {
    const messages = await ticketService.getTicketMessages(route.query.ticketId);
    // id: 123;
    // userId: 0
    // ticketId: 123123;
    // content: "ABCabc"
    // createdAt: "2025-05-25T17:20:13+00:00";
    // roles: Array [ "admin" ];


    if (messages && messages != null) {
      console.log("Fetching messages");
      pushMessagesToLocalList(messages);
    }
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
      message_id: item.id,
      text: item.content,
      sender: item.userId,
      timestamp: item.createdAt,
      roles: item.roles,
    };
    localMessageList.value.push(newItem);
  });
  toast.info("Carregando mensagens", {
    timeout: 3000,
  });
};

const submitMessage = () => {
  if (textInput.value.trim() === "") {
    toast.error("Erro: mensagem em branco!", {
      timeout: 3000,
    });

    return;
  }

  if (isTicketOpen.value) {
    addNewUserMessage(textInput.value);
    textInput.value = "";
  } else {
    createNewTicket(textInput.value);
    textInput.value = "";
  }

  // autoReply();
};

const addNewUserMessage = async (text) => {
  try {
    const userMessage = await ticketService.addNewMessage(route.query.ticketId, text);

    const lastUserMessage = {
          message_id: userMessage.messageId,
          text: userMessage.content,
          sender: "user",
          timestamp: new Date(),
          roles: ["user", "admin"],
        };
    console.log("Object = Last User Message");
    console.log(lastUserMessage);


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
    roles: [],
  });
};

const createNewTicket = async (text) => {
  try {
    let createdTicketId = 0;
    const createdTicket = await ticketService.createTicket(
      subject.value,
      text
    );
    createdTicketId = createdTicket.data.id;
    console.log("New ticketed creaded with ID:", createdTicketId);
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
  if (route.query.ticketId != 0) {
    toast.info(`Ticket REF# ${route.query.ticketId}`, { timeout: 3000 });
    isTicketOpen.value = true;
    fetchMessages();
  } else {
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
