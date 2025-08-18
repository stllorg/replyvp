package org.stll.reply.core.dtos;

import org.eclipse.microprofile.openapi.annotations.media.Schema;

import java.util.UUID;

@Schema(
        example = "{\"subject\": \"Assunto do novo ticket\", \"userId\": \"0123\"}"
)
public class SaveMessageRequest {
    public UUID ticketId;
    public UUID userId;
    public String message;
}
