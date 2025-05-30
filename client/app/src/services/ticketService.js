import api, { API_ENDPOINTS } from "./api";
import { getUserToken } from "@/services/authService";


const ticketService = {
  async getTickets() {
    const token = getUserToken();

    if (!token) {
      return false;
    }
    try {
      const response = await api.get(API_ENDPOINTS.USERS.TICKETS, {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });
      return response.data;
    } catch (error) {
      console.error("Erro ao buscar tickets:", error);
      throw error;
    }
  },
  async getPendingTickets() {
    const token = getUserToken();

    if (!token) {
      return false;
    }

    const ticketStatus = 'open'
    try {
      const response = await api.get(`${API_ENDPOINTS.TICKETS.BY_STATUS(ticketStatus)}`, {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });

      if (response.status === 200) {
        return response.data;
      }
    } catch (error) {
      console.error("Erro ao buscar tickets:", error);
      throw error;
    }
  },
  async createTicket(subject) { // create ticket and post first ticket message. returns array [ticket, message]
    const token = getUserToken();

    if (!token) return;

    try {
      const response = await api.post(
        `${API_ENDPOINTS.USERS.TICKETS}`,
        {
          subject: subject
        },
        {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        }
      );
      
      if (response.status === 201) {
        return response;
      }

      return response;
    } catch (error) {
      console.error("Erro ao criar ticket:", error);
      throw error;
    }
  },
  async getTicketById(ticketId) {
    const token = getUserToken();

    if (!token) {
      return false;
    }

    try {
      const response = await api.get(API_ENDPOINTS.TICKETS.BY_ID(ticketId), {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });
      
      return response.data;
    } catch (error) {
      console.error("Erro ao buscar tickets:", error);
      throw error;
    }
  },
  async addNewMessage(ticketId, messageContent) {
    const token = getUserToken();

    if (!token) {
      return false;
    }

    try {
      const response = await api.post(
        `${API_ENDPOINTS.TICKETS.MESSAGES(ticketId)}`,
        {
          content: messageContent,
        },
        {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      }
      );
      if (response.status === 201) {
        return response.data;
      }
    } catch (error) {
      this.toast.error("Ocorreu um erro ao conectar com o servidor!", {
        timeout: 3000,
      });
    }
  },
  async getTicketMessages(ticketId) {
    const token = getUserToken();

    if (!token) {
      return false;
    }

    try {
      const response = await api.get(
        `${API_ENDPOINTS.TICKETS.MESSAGES(ticketId)}`,
        {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        }
      );

      if (response.status === 200) {
        return response.data;
      } else {
        return response;
      }

    } catch (error) {
      console.error("Erro ao obter mensagens:", error);
      throw error;
    }
  },
};

export default ticketService;