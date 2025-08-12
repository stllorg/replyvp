package org.stll.dtos;

import lombok.AllArgsConstructor;
import lombok.NoArgsConstructor;

@NoArgsConstructor
@AllArgsConstructor
public class SaveTicketResponse {
    public String subject;
    public int ticketId;
    public int userId;
}
