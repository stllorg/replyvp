import axios from "axios";

const BASE_API_URL =  "http://localhost:8080";

const api = axios.create({
  baseURL: `${BASE_API_URL}`,
  headers: {
    "Content-Type": "application/json"
  }
});

export const API_ENDPOINTS = {
    USERS: {
      ROOT: '/users',
      BY_ID: (id) => `/users/${id}`,
      ROLES: (id) => `/users/${id}/roles`,
      INTERACTIONS: (id) => `/users/${id}/interactions`,
    },
    AUTH: {
      ROOT: '/auth',
      REGISTER: '/users',
      LOGIN:'/auth/login',
      AUTHENTICATE:'/auth/authenticate',
    },
    TICKETS: {
      ROOT: '/tickets',
      BY_ID: (id) => `/tickets/${id}`,
      BY_STATUS: (status) => `/tickets/${status}`,
      MESSAGES: (id) => `/tickets/${id}/messages`,
      },
  };

export default api;