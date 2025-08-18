package org.stll.reply.core.dtos;

import org.eclipse.microprofile.openapi.annotations.media.Schema;

@Schema(
        example = "{\"subject\": \"Assunto do novo ticket\"}"
)
public class CreateTicketRequest {
    public String subject;
}
