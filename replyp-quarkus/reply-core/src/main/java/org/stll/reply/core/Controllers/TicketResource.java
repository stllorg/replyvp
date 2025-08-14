package org.stll.reply.core.Controllers;

import jakarta.annotation.security.RolesAllowed;
import jakarta.inject.Inject;
import jakarta.ws.rs.*;
import jakarta.ws.rs.core.MediaType;
import jakarta.ws.rs.core.Response;
import org.stll.reply.core.Entities.Ticket;
import org.stll.reply.core.Services.TicketService;
import org.stll.reply.core.dtos.SaveTicketRequest;
import org.stll.reply.core.dtos.SaveTicketResponse;

import java.util.List;

@Path("/tickets")
@Produces(MediaType.APPLICATION_JSON)
@Consumes(MediaType.APPLICATION_JSON)
public class TicketResource {

    @Inject
    TicketService ticketService;

    // CREATE ticket
    @POST
    public Response createTicket(SaveTicketRequest request) {
        if (request == null) {
            return Response.status(Response.Status.BAD_REQUEST).entity("Ticket data is missing.").build();
        }

        Integer userId = request.userId;

        if (request.subject == null || userId == null) {
            return Response.status(Response.Status.BAD_REQUEST).entity("Subject and userId are required").build();
        }

        try {
            Ticket createdTicket = ticketService.createTicket(request.subject, request.userId);

            SaveTicketResponse saveTicketResponse = new SaveTicketResponse(createdTicket.getSubject(), createdTicket.getId(), createdTicket.getUserId());
            // return Response.created(URI.create("/tickets/" + createdTicket.getId())).entity(createdTicket).build();
            return Response.ok(saveTicketResponse).build();
        } catch (RuntimeException e) {
            return Response.status(Response.Status.BAD_REQUEST).entity(e.getMessage()).build();
        }
    }

    // GET ticket
    @GET
    @Path("/{id}")
    public Response getTicketById(@PathParam("id") int id) {
        return ticketService.findTicketById(id)
                .map(ticket -> Response.ok(ticket).build())
                .orElse(Response.status(Response.Status.NOT_FOUND).build());
    }

    // UPDATE ticket
    @PUT
    @Path("/{id}")
    public Response updateTicket(Ticket updatedTicket) {
        return ticketService.updateTicket(updatedTicket)
                .map(ticket -> Response.ok(ticket).build())
                .orElse(Response.status(Response.Status.NOT_FOUND).build());
    }

    // DELETE ticket
    @DELETE
    @Path("/{id}")
    @RolesAllowed("admin")
    public Response deleteTicketById(@PathParam("id") int id) {
        boolean isDeleted = ticketService.delete(id);
        if (isDeleted) {
            return Response.status(Response.Status.NO_CONTENT).build();
        } else {
            return Response.status(Response.Status.NOT_FOUND).build();
        }
    }

    // GET ALL Open TICKETS
    @GET
    @Path("/open")
    public Response getAllOpenTickets() {
        List<Ticket> openTickets = ticketService.findAllOpenTickets();
        return Response.ok(openTickets).build();
    }

    // GET ALL TICKETS created by a User
    @GET
    @Path("/user/{userId}")
    public Response getTicketsByUserId(@PathParam("id") int id) {
        List<Ticket> tickets = ticketService.findTicketsByUserId(id);
        return Response.ok(tickets).build();
    }

    // GET Tickets Ids with User Messages By User Id
    @GET
    @Path("/user/{userId}/tickets")
    public Response getTicketIdsByUserId(@PathParam("userId") int userId) {
        List<Integer> ticketIds = ticketService.getTicketIdsWithUserMessagesByUserId(userId);
        return Response.ok(ticketIds).build();
    }
}
