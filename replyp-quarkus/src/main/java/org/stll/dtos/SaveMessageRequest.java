package org.stll.dtos;

import org.eclipse.microprofile.openapi.annotations.media.Schema;

@Schema(
        example = "{\"subject\": \"Assunto do novo ticket\", \"userId\": \"0123\"}"
)
public class SaveMessageRequest {
    public int ticketId;
    public int userId;
    public String message;
}
