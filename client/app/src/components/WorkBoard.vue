<template>
  <div class="board container-fluid shadow-sm rounded-3 my-4 mx-auto">
    <div class="row text-white align-items-center py-2 px-3">
      <div class="col-6">
        <h1 class="fs-4 fw-medium mb-0">{{ boardTitle }}</h1>
        <i class="bi bi-arrow-left" @click="goBack()" role="button" title="Atualizar Cadastro"></i>
      </div>
      <div class="col-6 text-end">
        <i class="bi bi-arrow-clockwise"></i>
        <i class="bi bi-three-dots"></i>
      </div>
    </div>
    
    <div class="row py-2 px-3">
      <div class="col-12">
        <div class="input-group">
          <span class="input-group-text bg-light border-0 rounded-start-5 pe-0" id="search-addon">
            <i class="bi bi-search text-muted"></i></span>
          <input type="text" class="form-control bg-light border-0 rounded-end-5 ps-0" placeholder="Buscar usuário..."
            aria-label="Search" aria-describedby="search-addon" />
        </div>
      </div>
    </div>

    <ul v-if="tickets?.length === 0" class="list-group list-group-flush">
      <p class="text-center text-truncate text-secondary">
        Sem histórico de tickets.
      </p>
    </ul>
    <div v-else class="list-group list-group-flush">
      <a v-for="(ticket, index) in tickets" :key="index" :item="ticket" href="#"
        class="list-group-item list-group-item-action py-3 px-3 border-bottom">
        <div class="d-flex align-items-center">
          <img src="https://placehold.co/50x50.png" alt="Contact Picture" class="rounded-circle me-3 avatar-sm" />
          <div class="flex-grow-1">
            <div class="d-flex justify-content-between align-items-center">
              <h6 class="mb-1 fw-medium text-dark">
                {{ truncate(ticket.subject) }}
              </h6>
            </div>
            <p class="mb-0 text-muted text-truncate">
              Aberto em : {{ formatFullDateTime(ticket.createdAt) }}
            </p>
          </div>
          <div class="d-flex">
            <button class="btn btn-outline-primary btn-sm me-2" @click="$emit('view-messages', ticket.id)"
              title="Ver Mensagens">
              <i class="bi bi-chat-dots"></i>
            </button>
            <button class="btn btn-outline-primary btn-sm me-2" @click="$emit('archive-ticket', ticket.id)"
              title="Arquivar ticket">
              <i class="bi bi-archive"></i>
            </button>
          </div>
        </div>
      </a>
    </div>

    
  </div>
</template>

<script setup>
import { formatFullDateTime } from "@/utils/dateUtils";
import { useRouter } from "vue-router";

const router = useRouter();

defineProps({
  boardTitle: String,
  tickets: Array,
});

defineEmits(["view-messages", "archive-ticket"]);

function truncate(text, maxLength = 40) {
  return text?.length > maxLength ? text.slice(0, maxLength) + "…" : text;
}

const goBack = () => {
  router.back();
};
</script>
