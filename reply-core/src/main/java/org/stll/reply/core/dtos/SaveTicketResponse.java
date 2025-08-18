package org.stll.reply.core.dtos;

import lombok.AllArgsConstructor;
import lombok.NoArgsConstructor;

import java.util.UUID;

@NoArgsConstructor
@AllArgsConstructor
public class SaveTicketResponse {
    public String subject;
    public UUID ticketId;
    public UUID userId;
}
