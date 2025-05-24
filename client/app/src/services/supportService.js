import axios from "axios";
import { API_ENDPOINTS } from "./api";
import authService from "@/services/authService";

const supportService = {
  
  async assistTicket() {
    const token = authService.getUserToken();
    
    if (!token) {
      return false;
    }
    
    try {
      let newTicketStatus = 'in_progress';
      const response = await axios.get(
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