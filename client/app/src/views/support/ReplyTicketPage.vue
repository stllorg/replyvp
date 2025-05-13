<template>
  <div class="chat-container">
    <div class="chat-box">
      <div
        v-for="(msg, index) in localList"
        :key="index"
        class="message"
        :class="msg.sender === 'user' ? 'user-message' : 'support-message'"
      >
        <p>{{ msg.text }}</p>
        <small>{{ formatMessageTime(msg.timestamp) }}</small>
      </div>
    </div>
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
import { formatMessageTime } from "@/utils/dateUtils";
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import { useToast } from "vue-toastification";

const toast = useToast();
const router = useRouter();

const user = ref(JSON.parse(localStorage.getItem("user")));
const ticketId = ref(router.query.ref || 0);
const newMessage = ref(router.query.message || "");
const localList = ref([
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

onMounted(async () => {
  getChatHistory();
});

const getChatHistory = async () => {
  // TODO: Use Auth Bearer with token to send user id
  if (!user.value || !user.value.token) {
    toast.error("Falha na autenticação!", { timeout: 3000 });
    // TODO: Redirect user
  }
  try {
    const response = await ticketService.getTicketMessages(ticketId.value, user.value.id);
    pushChatHistoryToLocalList(response);
  } catch (err) {
    toast.error("Ocorreu um erro ao carregar mensagens do ticket!", {
      timeout: 3000,
    });
  }
};

const pushChatHistoryToLocalList = (data = []) => {
  data.forEach((item) => {
    const newItem = {
      message_id: item.message_id,
      text: item.text,
      sender: item.sender,
      timestamp: item.created_at,
    };
    localList.value.push(newItem);
  });
  toast.info("Carregando mensagens", {
    timeout: 3000,
  });
};

const submitNewMessage = async () => {
  if (newMessage.value.trim() === "") {
    toast.error("Erro: Campo de mensagem está em branco!", { timeout: 3000 });
    return;
  }

  try {
    const response = await ticketService.addNewMessage(
      ticketId.value,
      user.value.id,
      newMessage.value
    );

    localList.value.push(response);
    newMessage.value = "";

    setTimeout(fakeReply, 1000);
  } catch (err) {
    console.error(err);
    toast.error("Ocorreu um erro ao conectar com o servidor!", { timeout: 3000 });
  }
};

const fakeReply = () => {
  localList.value.push({
    text: "Estamos verificando sua solicitação.",
    sender: "support",
    timestamp: new Date(),
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
