package org.stll.service_provider;

import jakarta.annotation.security.RolesAllowed;
import jakarta.inject.Inject;
import jakarta.ws.rs.*;
import jakarta.ws.rs.core.MediaType;
import jakarta.ws.rs.core.Response;
import org.eclipse.microprofile.config.inject.ConfigProperty;
import org.eclipse.microprofile.rest.client.inject.RestClient;

@Path("/api/v1/tickets")
@Produces(MediaType.APPLICATION_JSON)
@Consumes(MediaType.APPLICATION_JSON)
public class TicketResource {

    @Inject
    @RestClient
    BackendRestClient backendRestClient;

    @ConfigProperty(name = "your-api.security.api-key")
    String apiKey;

    // Create ticket
    @POST
    @RolesAllowed("user")
    public Response createTicket(String ticketPayload) {
        return backendRestClient.createTicket(apiKey, ticketPayload);
    }

    // Get ticket by Id
    @GET
    @Path("/{id}")
    @RolesAllowed({"admin", "user", "support"})
    public Response getTicketById(@PathParam("id") String id) {
        return backendRestClient.getTicketById(apiKey, id);
    }

    // GET tickets Ids with user messages by user id
    @GET
    @Path("/{id}")
    @RolesAllowed({"admin", "user", "support"})
    public Response getTicketsIdsWithUserMessage(@PathParam("id") String id) {
        return backendRestClient.getTicketById(apiKey, id);
    }

    // Update ticket by Id
    @PUT
    @Path("/{id}")
    @RolesAllowed({"admin", "user", "support"})
    public Response updateTicket(@PathParam("id") String id,  String updatedTicketPayload) {
        return backendRestClient.updateTicket(apiKey, updatedTicketPayload);
    }

    // Delete ticket by Id
    @DELETE
    @Path("/{id}")
    @RolesAllowed({"admin", "user", "support"})
    public Response deleteTicketById(@PathParam("id") String id) {
        return backendRestClient.deleteTicketById(apiKey, id);
    }

    // Get all tickets from a user
    @GET
    @Path("/users/{id}")
    @RolesAllowed({"admin", "user", "support"})
    public Response getTicketsByUserId(@PathParam("id") String id) {
        return backendRestClient.getAllTicketsByUserId(apiKey, id);
    }

    // Get all pending tickets
    @GET
    @Path("/open}")
    @RolesAllowed({"admin", "user", "support"})
    public Response getAllPendingTickets() {
        return backendRestClient.getAllPendingTickets(apiKey);
    }
}