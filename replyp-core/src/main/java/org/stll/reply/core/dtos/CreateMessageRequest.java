package org.stll.reply.core.dtos;

import jakarta.validation.constraints.NotBlank;
import jakarta.validation.constraints.Size;
import org.eclipse.microprofile.openapi.annotations.media.Schema;

@Schema(
        example = "{\"message\": \"texto\"}"
)
public class CreateMessageRequest {
    // message represents the Message text content
    @NotBlank(message = "Message content cannot be blank")
    @Size(min = 5, max = 1000, message = "Message content must be between 5 and 1000 characters")
    public String message;
}
