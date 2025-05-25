<template>
  <div class="chat-container">
    <div class="chat-box">
      <div v-for="(msg, index) in localMessageList" :key="index" :msg="msg"
      class="message" :class="msg.sender === 'user' ? 'user-message' : 'support-message'">
      <p>{{ msg.content }}</p>
      <small>{{ formatMessageTime(msg.createdAt) }}</small>
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
]);

const textInput = ref("-");
const isTicketOpen = ref(false);

onMounted(
() => {
  if (route.query && route.query.ticketId != 0) {
    toast.info(`Ticket REF# ${route.query.ticketId}`, { timeout: 3000 });
    isTicketOpen.value = true;
    fetchMessages();
  } 

}
);

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

const pushMessagesToLocalList = (data = []) => { 
  if (!data) return;

  data.forEach((item) => {
    const newItem = {
      id: item.id,
      content: item.content,
      sender: item.userId,
      createdAt: item.createdAt,
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
    // TODO: Check if user is not staff
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
    id: localMessageList.value.length + 1,
    content: "Estamos verificando sua solicitação.",
    sender: "none",
    createdAt: new Date(),
    roles: [],
  });
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
