<template>
  <div class="board container-fluid shadow-sm rounded-3 my-4 mx-auto">
    <div class="row text-white align-items-center py-2 px-3">
      <div class="col-6">
        <h1 class="fs-4 fw-medium mb-0">Gerenciar Usuários</h1>
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
            aria-label="Search" aria-describedby="search-addon">
        </div>
      </div>
    </div>

    <div class="list-group list-group-flush">

      <a v-for="(item, index) in accounts" :key="index" :item="item" href="#"
        class="list-group-item list-group-item-action py-3 px-3 border-bottom">
        <div class="d-flex align-items-center">
          <img src="https://placehold.co/50x50.png" alt="Contact Picture" class="rounded-circle me-3 avatar-sm">
          <div class="flex-grow-1">
            <div class="d-flex justify-content-between align-items-center">
              <h6 class="mb-1 fw-medium text-dark">{{ item.username }}</h6>
            </div>
            <p class="mb-0 text-muted text-truncate">{{ item.email }}</p>
          </div>
          <small class="text-secondary text-opacity-75">
            <i class="bi bi-pencil-square btn btn-outline-primary p-2" @click="openEditor(item.id)" role="button"
              title="Atualizar Cadastro"></i>
          </small>
          <div v-if="showModal" class="modal-backdrop">
            <div class="modal-content p-4 rounded shadow bg-white w-50">
              <h5>Edit account with id: {{ selectedId }}</h5>
              <select v-model="selectedRole" class="form-select my-3">
                <option value="user">User</option>
                <option value="admin">Admin</option>
                <option value="manager">Manager</option>
                <option value="support">Support</option>
              </select>

              <div v-if="selectedRole !== originalRole" class="d-flex gap-3">
                <i class="bi bi-x-circle text-danger fs-4" role="button" @click="cancelEdit"></i>
                <i class="bi bi-check-circle text-success fs-4" role="button" @click="confirmEdit"></i>
              </div>
            </div>
          </div>
        </div>
      </a>

    </div>
  </div>
</template>

<script setup>
import { getUserRoleById, updateUserRoleById } from '@/services/userService';
import { onMounted, ref } from 'vue';
import { useRouter } from "vue-router";
import { useToast } from "vue-toastification";
import { getAllUsers } from "@/services/userService";


const router = useRouter();
const toast = useToast();

const usersPage = 1;
const usersPerPage = 15;
const totalUsersPages = ref(15);


const accounts = ref([]);
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
    }
  ];

const showModal = ref(false);
const selectedId = ref(null);
const originalRole = ref('');
const selectedRole = ref('');

onMounted(
  async () => {
    fetchAccounts();
  }
);

const fetchAccounts = async () => {
  try {
    const response = await getAllUsers(usersPage, usersPerPage);

    if (!response || response.status !== 200) {
      console.log("Failure: Response code is not 200.");
      return;
    }
    
    const { users, pagination } = response.data ?? {};

    if (!users) {
      console.warn("Users data missing in response.");
      return;
    }

    if (pagination?.totalPages > 1) {
        totalUsersPages.value = pagination.totalPages;
    }

    accounts.value.push(...users);
  } catch (err) {
    console.log("Error while fetching user accounts:", err);
    console.log("Loading default accounts");
    accounts.value.push(...defaultAccounts);
  }
};

const goBack = ()=> {
  router.back();
};

async function openEditor(id) {
  const mainRole = await getUserMainRole(id);

  selectedId.value = id;
  originalRole.value = mainRole;
  selectedRole.value = mainRole;
  showModal.value = true;
}

function cancelEdit() {
  showModal.value = false;
}

function confirmEdit() {
  updateUserRole(selectedId.value, selectedRole.value);
  showModal.value = false;
}

async function getUserMainRole(id) {
  const response = await getUserRoleById(id);
  const targetUserRoles = response.data.roles;

  if (targetUserRoles.includes('admin')) {
    console.log('Role admin detected');
    return "admin";
  } else if (targetUserRoles.includes('manager')) {
    console.log('Role Manager detected');
    return "manager";
  } else if (targetUserRoles.includes('support')) {
    console.log('Role Support detected');
    return "support";
  }
  
  console.log('Role user set as default');
  return 'user';
}

function updateUserRole(id, newRole) {
  console.log(`Updating user ${id} to role ${newRole}`);
  const response = updateUserRoleById(id, newRole);

  if (response.status && response.status == 204) {
    toast.success("Sucesso ao atualizar a conta!", { timeout: 3000 });
  }
}
</script>

<style>
.board {
  max-width: auto;
  background-color: gray;
}
</style>