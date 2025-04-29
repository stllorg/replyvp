import axios from "axios";

const SUPPORT_API_URL = "http://localhost:8080/api/tickets";

const supportService = {
  
  async assistTicket(ticketId, token) {
    try {

      const response = await axios.get(
        `${SUPPORT_API_URL}/treat_ticket.php`,
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