import api, { API_ENDPOINTS } from "./api";
import { getUserToken } from "@/services/authService";


const ticketService = {
  async getTickets() { // Get tickets from the logged user
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
        return response.data.tickets;
      }
    } catch (error) {
      console.error("Erro ao buscar tickets:", error);
      throw error;
    }
  },
  async createTicket(subject, message) {
    const token = getUserToken();
    
    if (!token) {
      return false;
    }

    try {
      const response = await api.post(`${API_ENDPOINTS.TICKETS}`, {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      },
      {
        subject: subject,
        message: message,
      });

      if (response.status === 201) {
        return response.data.ticket_id;
      }
    } catch (error) {
      console.error("Erro ao criar ticket:", error);
      throw error;
    }
  },
  async addNewMessage(ticketId, messageContent){
    const token = getUserToken();
    
    if (!token) {
      return false;
    }

      try {
        const response = await api.post(
          `${API_ENDPOINTS.TICKETS.MESSAGES(ticketId)}`, {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          },
          {
            ticket_id: ticketId,
            message: messageContent,
          }
        );
        if (response.status === 201) {
          const lastUserMessage = {
            message_id: response.data.added.message_id,
            text: response.data.added.text,
            sender: "user",
            timestamp: response.data.added.compact,
          };
          return lastUserMessage;
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
      `${API_ENDPOINTS.TICKETS.MESSAGES(ticketId)}`, {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );

      if (response.status === 200) {
        return response.data.messages;
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