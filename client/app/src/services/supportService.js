import api, { API_ENDPOINTS } from "./api";
import { getUserToken } from "@/services/authService";

const supportService = {
  
  async assistTicket() {
    const token = getUserToken();
    
    if (!token) {
      return false;
    }
    
    try {
      let newTicketStatus = 'in_progress';
      const response = await api.get(
        API_ENDPOINTS.TICKETS.BY_STATUS(newTicketStatus),
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