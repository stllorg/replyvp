import api, { API_ENDPOINTS } from "./api";
import { getUserToken } from "@/services/authService";

const supportService = {
  
  async assistTicket(ticketId) {
    const token = getUserToken();
    
    if (!token) {
      return false;
    }
    
    try {
      // Change ticket status to in_progress
      let newTicketStatus = 'in_progress';

      const response = await api.post(
        API_ENDPOINTS.TICKETS.BY_ID(ticketId),
        {
          newStatus: newTicketStatus
        },
        {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        }
      );

      if (response.status === 200) {
        return response;
      } else {
        return response;
      } 
    } catch (error) {
    console.log(error);
    }
  },
    
};

export default supportService;