<template>
  <div class="card shadow border-0" style="background-color: #f8f9fa">
    <div class="card-body">
      <h5 class="card-title text-secondary fw-bold">Atendimentos do Usuário</h5>

      <ul v-if="tickets?.length === 0" class="list-group list-group-flush">
        <p class="text-center text-truncate text-secondary">Sem histórico de tickets.</p>
      </ul>

      <ul v-else class="list-group list-group-flush">
        <li v-for="ticket in tickets" :key="ticket.id"
          class="list-group-item d-flex justify-content-between align-items-center"
          style="background-color: #f8f9fa; border: none">
          <div>
            <small class="text-muted">
              {{ formatFullDateTime(ticket.createdAt) }}</small>
          </div>
          <div class="text-truncate text-secondary text-decoration-underline" style="max-width: 70%">
            <router-link v-if="ticket.id != 0" :to="{ name: 'MessagesPage', query: { ticketId: ticket.id, }, }"
              class="text-truncate text-secondary subject-link">
              {{ truncate(ticket.subject) }}
            </router-link>
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
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup>
import { formatFullDateTime } from "@/utils/dateUtils";

defineProps({
  tickets: Array,
})

defineEmits(['view-messages', 'archive-ticket'])

function truncate(text, maxLength = 40) {
  return text?.length > maxLength ? text.slice(0, maxLength) + '…' : text
}
</script>