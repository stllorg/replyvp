<template>
  <div class="board container-fluid shadow-sm rounded-3 my-4 mx-auto">
    <div class="row text-white align-items-center py-2 px-3">
      <div class="col-6">
        <h1 class="fs-4 fw-medium mb-0">Gerenciar Usuários</h1>
        <i class="bi bi-arrow-left" @click="goBack()" role="button"
                title="Atualizar Cadastro"></i>
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
            aria-label="Search" aria-describedby="search-addon">
        </div>
      </div>
    </div>

    <div class="list-group list-group-flush">

      <a v-for="(item, index) in localAccounts" :key="index" :item="item" href="#"
        class="list-group-item list-group-item-action py-3 px-3 border-bottom">
        <div class="d-flex align-items-center">
          <img src="https://placehold.co/50x50.png" alt="Contact Picture" class="rounded-circle me-3 avatar-sm">
          <div class="flex-grow-1">
            <div class="d-flex justify-content-between align-items-center">
              <h6 class="mb-1 fw-medium text-dark">{{ item.username }}</h6>
              <small class="text-secondary text-opacity-75">
                <i class="bi bi-pencil-square btn btn-outline-primary p-2" @click="openEditor(item.id)" role="button"
                title="Atualizar Cadastro"></i>
              </small>
            </div>
            <p class="mb-0 text-muted text-truncate">{{ item.email }}</p>
          </div>
        </div>
      </a>

    </div>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { useRouter } from "vue-router";
const router = useRouter();


const localAccounts = ref([]);


onMounted(
  () => {
    fetchAccounts();
  }
);

const fetchAccounts = async () => {
  loadDefaultAccounts();
};


const loadDefaultAccounts = () => {
  const defaultAccounts = [
    {
      id: 0,
      username: "John",
      email: 'test@test.com',
      roles: ['admin'],
      createdAt: new Date().toISOString,
    },
    {
      id: 1,
      username: "Smith",
      email: 'test@test.com',
      roles: ['admin'],
      createdAt: new Date().toISOString,
    },
    {
      id: 0,
      username: "John",
      email: 'test@test.com',
      roles: ['admin'],
      createdAt: new Date().toISOString,
    },
    {
      id: 1,
      username: "Smith",
      email: 'test@test.com',
      roles: ['admin'],
      createdAt: new Date().toISOString,
    },
    {
      id: 0,
      username: "John",
      email: 'test@test.com',
      roles: ['admin'],
      createdAt: new Date().toISOString,
    },
    {
      id: 1,
      username: "Smith",
      email: 'test@test.com',
      roles: ['admin'],
      createdAt: new Date().toISOString,
    },
    {
      id: 0,
      username: "John",
      email: 'test@test.com',
      roles: ['admin'],
      createdAt: new Date().toISOString,
    },
    {
      id: 1,
      username: "Smith",
      email: 'test@test.com',
      roles: ['admin'],
      createdAt: new Date().toISOString,
    },
    {
      id: 0,
      username: "John",
      email: 'test@test.com',
      roles: ['admin'],
      createdAt: new Date().toISOString,
    },
    {
      id: 1,
      username: "Smith",
      email: 'test@test.com',
      roles: ['admin'],
      createdAt: new Date().toISOString,
    },
    {
      id: 0,
      username: "John",
      email: 'test@test.com',
      roles: ['admin'],
      createdAt: new Date().toISOString,
    },
    {
      id: 1,
      username: "Smith",
      email: 'test@test.com',
      roles: ['admin'],
      createdAt: new Date().toISOString,
    },
    {
      id: 1,
      username: "Smith",
      email: 'test@test.com',
      roles: ['admin'],
      createdAt: new Date().toISOString,
    },
    {
      id: 0,
      username: "John",
      email: 'test@test.com',
      roles: ['admin'],
      createdAt: new Date().toISOString,
    },
    {
      id: 1,
      username: "Smith",
      email: 'test@test.com',
      roles: ['admin'],
      createdAt: new Date().toISOString,
    },
  ];

  localAccounts.value.push(...defaultAccounts);
}
const openEditor = (id) => {
  console.log("Edit account", id);
};

const goBack = ()=> {
  router.back();
};

</script>

<style>
.board {
  max-width: auto;
  background-color: gray;
}
</style>