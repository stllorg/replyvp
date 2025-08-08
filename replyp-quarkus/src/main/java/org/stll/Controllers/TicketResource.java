package org.stll.Controllers;

import jakarta.annotation.security.RolesAllowed;
import jakarta.inject.Inject;
import jakarta.ws.rs.*;
import jakarta.ws.rs.core.MediaType;
import jakarta.ws.rs.core.Response;
import org.stll.Entities.Ticket;
import org.stll.Services.TicketService;

import java.net.URI;
import java.util.List;

@Path("/tickets")
@Produces(MediaType.APPLICATION_JSON)
@Consumes(MediaType.APPLICATION_JSON)
public class TicketResource {

    @Inject
    TicketService ticketService;

    // CREATE ticket
    @POST
    public Response createTicket(Ticket newTicket) {
        if (newTicket == null) {
            return Response.status(Response.Status.BAD_REQUEST).entity("Ticket data is missing.").build();
        }

        Integer userId = newTicket.getUserId();

        if (newTicket.getSubject() == null || userId == null) {
            return Response.status(Response.Status.BAD_REQUEST).entity("Subject and userId are required").build();
        }
        Ticket createdTicket = ticketService.createTicket(newTicket);
        return Response.created(URI.create("/tickets/" + createdTicket.getId())).entity(createdTicket).build();
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
