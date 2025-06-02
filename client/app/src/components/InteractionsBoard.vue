<template>
  <div class="col-12">
    <div class="card mb-2 main-card">
      <div class="col-md-12 main-concept2">
        <div class="card shadow">
          <div class="d-flex align-items-start gap-2">
            <i class="bi bi-arrow-left" @click="goBack()" role="button" title="Atualizar Cadastro"></i>
            <h5>Back to Staff Area</h5>
          </div>
          <div class="position-absolute top-0 end-0 p-3">
            <i class="bi bi-three-dots"></i>
          </div>

          <div v-if="isSearchTabActive" class="card-body p-4">
            <ul class="nav nav-tabs mb-4 justify-content-center">
              <li class="nav-item">
                <a class="nav-link active" href="#"><i class="bi bi-search"></i>Pesquisar Ticket</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" :class="{ active: isInteractionsTabActive }" href="#"
                  @click.prevent="setActiveTab('interactions')"><i class="bi bi-clock-history"></i>{{ boardTitle }}</a>
              </li>
            </ul>

            <div class="board container-fluid shadow-sm rounded-3 my-4 mx-auto">
              <div class="row text-white align-items-center py-2 px-3"></div>

              <div class="row py-2 px-3">
                <div class="col-12">
                  <div class="input-group">
                    <span class="input-group-text bg-light border-0 rounded-start-5 pe-0" id="search-addon">
                      <i class="bi bi-search text-muted"></i></span>
                    <input type="text" class="form-control bg-light border-0 rounded-end-5 ps-0"
                      placeholder="Buscar ticket..." aria-label="Search" aria-describedby="search-addon" />
                  </div>
                </div>

                <div class="d-grid gap-2 mb-3 py-2">
                  <button type="submit" class="btn btn-primary" @click="handleSearch()" title="Pesquisar termos">
                    Pesquisar agora
                  </button>
                </div>
              </div>

              <ul class="list-group list-group-flush">
                <p class="text-center text-truncate text-primary">
                  Sem histórico de tickets.
                </p>
              </ul>
              <div class="list-group list-group-flush">

                <a href="#" class="list-group-item list-group-item-action py-3 px-3 border-bottom">
                  <div class="d-flex align-items-center">
                    <img src="https://placehold.co/50x50.png" alt="Contact Picture"
                      class="rounded-circle me-3 avatar-sm" />
                    <div class="flex-grow-1">
                      <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-1 fw-medium text-dark">
                          Texto (subject)
                        </h6>
                      </div>
                      <p class="mb-0 text-muted text-truncate">
                        Aberto em : Data aqui
                      </p>
                    </div>
                    <div class="d-flex">
                      <button class="btn btn-outline-primary btn-sm me-2" @click="$emit('view-messages', 0)"
                        title="Ver Mensagens">
                        <i class="bi bi-chat-dots"></i>
                      </button>
                      <button class="btn btn-outline-primary btn-sm me-2" @click="$emit('archive-ticket', 0)"
                        title="Arquivar ticket">
                        <i class="bi bi-archive"></i>
                      </button>
                    </div>
                  </div>
                </a>
                <a href="#" class="list-group-item list-group-item-action py-3 px-3 border-bottom">
                  <div class="d-flex align-items-center">
                    <img src="https://placehold.co/50x50.png" alt="Contact Picture"
                      class="rounded-circle me-3 avatar-sm" />
                    <div class="flex-grow-1">
                      <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-1 fw-medium text-dark">
                          Texto (subject)
                        </h6>
                      </div>
                      <p class="mb-0 text-muted text-truncate">
                        Aberto em : Data aqui
                      </p>
                    </div>
                    <div class="d-flex">
                      <button class="btn btn-outline-primary btn-sm me-2" @click="$emit('view-messages', 0)"
                        title="Ver Mensagens">
                        <i class="bi bi-chat-dots"></i>
                      </button>
                      <button class="btn btn-outline-primary btn-sm me-2" @click="$emit('archive-ticket', 0)"
                        title="Arquivar ticket">
                        <i class="bi bi-archive"></i>
                      </button>
                    </div>
                  </div>
                </a>
              </div>
            </div>
          </div>
          <div v-if="isInteractionsTabActive" class="card-body p-4">
            <ul class="nav nav-tabs mb-4 justify-content-center">
              <li class="nav-item">
                <a class="nav-link" :class="{ active: isSearchTabActive }" href="#"
                  @click.prevent="setActiveTab('search')"><i class="bi bi-search"></i>Pesquisar Ticket</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" href="#"><i class="bi bi-clock-history"></i> {{ boardTitle }}</a>
              </li>
            </ul>

            <div class="card shadow border-0" style="background-color: #f8f9fa">
              <div class="card-body">
                <ul v-if="interactions?.length === 0" class="list-group list-group-flush">
                  <p class="text-center text-truncate text-secondary">
                    Sem histórico de interações.
                  </p>
                </ul>
                <div v-else class="list-group list-group-flush">
                  <a v-for="(ticket, index) in interactions" :key="index" :item="ticket" href="#"
                    class="list-group-item list-group-item-action py-3 px-3 border-bottom">
                    <div class="d-flex align-items-center">
                      <img src="https://placehold.co/50x50.png" alt="Contact Picture"
                        class="rounded-circle me-3 avatar-sm" />
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
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import { formatFullDateTime } from "@/utils/dateUtils";

defineProps({
  boardTitle: String,
  interactions: Array,
});

defineEmits(["view-messages", "archive-ticket"]);

const isSearchTabActive = ref(true);
const isInteractionsTabActive = ref(false);
const router = useRouter();

// function formatDate(dateStr) {
//  return new Date(dateStr).toLocaleString('pt-BR')
//}

function truncate(text, maxLength = 40) {
  return text?.length > maxLength ? text.slice(0, maxLength) + "…" : text;
}

function handleSearch() {
  console.log("Button pressed.");
}

const setActiveTab = (tabName) => {
  if (tabName === "search") {
    isSearchTabActive.value = true;
    isInteractionsTabActive.value = false;
  } else if (tabName === "interactions") {
    isSearchTabActive.value = false;
    isInteractionsTabActive.value = true;
  }
};

const goBack = () => {
  router.back();
};
</script>
