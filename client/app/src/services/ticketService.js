import axios from "axios";

const TICKETS_API_URL = "http://localhost:8080/api/tickets";

const ticketService = {
  async getTickets(userId) {
    try {
      const response = await axios.get(`${TICKETS_API_URL}/list.php`, {
        params: { id: userId },
      });
      return response.data.tickets;
    } catch (error) {
      console.error("Erro ao buscar tickets:", error);
      throw error;
    }
  },
  async getPendingTickets(supportId, token) {
    // TODO: Compare supportId with token id
    console.log(supportId);
    try {
      const response = await axios.get(`${TICKETS_API_URL}/open_tickets.php`, {
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
  async createTicket(userId, subject, message) {
    try {
      const response = await axios.post(`${TICKETS_API_URL}/create.php`, {
        user_id: userId,
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
  async addNewMessage(ticketId, userId, messageContent){
      try {
        const response = await axios.post(
          `${TICKETS_API_URL}/new_message.php`,
          {
            ticket_id: ticketId,
            user_id: userId,
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
  async getTicketMessages(ticketId, userId) {
    try {
      const response = await axios.get(
        `${TICKETS_API_URL}/retrieve_messages.php`,
        {
          params: {
            ticket_id: ticketId,
            user_id: userId,
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