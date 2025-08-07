package org.stll.Controllers;

import jakarta.inject.Inject;
import jakarta.ws.rs.*;
import jakarta.ws.rs.core.MediaType;
import jakarta.ws.rs.core.Response;
import org.stll.Entities.Message;
import org.stll.Services.MessageService;

import java.net.URI;
import java.util.List;

@Path("/messages")
@Produces(MediaType.APPLICATION_JSON)
@Consumes(MediaType.APPLICATION_JSON)
public class MessageResource {

    @Inject
    MessageService messageService;

    @POST
    @Path("/{ticketId}")
    public Response createMessage(@PathParam("ticketId") int ticketId, Message message) {


        if (message == null) {
            return Response.status(Response.Status.BAD_REQUEST).entity("Message data is missing.").build();
        }

        Integer userId = message.getUserId();
        Integer newMessageTicketId = message.getTicketId();


        if (message.getMessage() == null || userId == null || newMessageTicketId == null) {
            return Response.status(Response.Status.BAD_REQUEST).entity("Message content and IDs are required").build();
        }
        Message createdMessage = messageService.createMessage(message);
        return Response.created(URI.create("/messages/" + createdMessage.getId())).entity(createdMessage).build();
    }

    @GET
    @Path("/ticket/{ticketId}")
    public Response getMessagesByTicketId(@PathParam("ticketId") int ticketId) {
        List<Message> messages = messageService.getMessagesByTicketId(ticketId);
        return Response.ok(messages).build();
    }

    @GET
    @Path("/user/{userId}/tickets")
    public Response getTicketIdsByUserId(@PathParam("userId") int userId) {
        List<Integer> ticketIds = messageService.getTicketIdsWithUserMessagesByUserId(userId);
        return Response.ok(ticketIds).build();
    }
}
