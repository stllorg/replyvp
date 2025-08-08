package org.stll.service_provider;

import jakarta.annotation.security.RolesAllowed;
import jakarta.ws.rs.*;
import jakarta.ws.rs.core.Response;
import org.eclipse.microprofile.config.inject.ConfigProperty;
import org.eclipse.microprofile.rest.client.inject.RegisterRestClient;
import org.stll.service_provider.dtos.SaveMessageRequest;
import org.stll.service_provider.dtos.SaveTicketRequest;
import org.stll.service_provider.dtos.SaveUserRequest;

@RegisterRestClient(configKey = "your-api")
@Path("/api/v1")
public interface BackendRestClient {

    @ConfigProperty(name = "your-api.security.api-key")
    String apiKey = "123";


    // AUTH

    // login
    @POST
    @Path("/login")
    @Consumes("application/json")
    @Produces("application/json")
    Response login(@HeaderParam("X-API-KEY") String apiKey, String loginPayload);

    // register
    @POST
    @Path("/register")
    @Consumes("application/json")
    @Produces("application/json")
    Response register(@HeaderParam("X-API-KEY") String apiKey, String registerPayload);


    // USER

    // CREATE user
    @POST
    @Path("/users")
    @Consumes("application/json")
    Response createUser(@HeaderParam("X-API-KEY") String apiKey, SaveUserRequest userPayload);

    // UPDATE user
    @PUT
    @Path("/users/{id}")
    @Consumes("application/json")
    Response updateUser(@HeaderParam("X-API-KEY") String apiKey, String updatedUserPayload);

    // GET user
    @GET
    @Path("/users/{id}")
    Response getUserById(@HeaderParam("X-API-KEY") String apiKey, @PathParam("id") String id);

    // DELETE user
    @DELETE
    @Path("/{id}")
    public Response deleteUserById(@HeaderParam("X-API-KEY") String apiKey, @PathParam("id") String id);


    // TICKET

    // CREATE ticket
    @POST
    @Path("/tickets")
    @Consumes("application/json")
    Response createTicket(@HeaderParam("X-API-KEY") String apiKey, SaveTicketRequest ticketPayload);

    // UPDATE ticket
    @PUT
    @Path("/tickets/{id}")
    @Consumes("application/json")
    Response updateTicket(@HeaderParam("X-API-KEY") String apiKey, String updateTicketPayload);

    // GET ticket
    @GET
    @Path("/tickets/{id}")
    Response getTicketById(@HeaderParam("X-API-KEY") String apiKey, @PathParam("id") String id);

    // GET ALL tickets by user id
    @GET
    @Path("/tickets/users/{id}")
    Response getAllTicketsByUserId(@HeaderParam("X-API-KEY") String apiKey, @PathParam("id") String id);

    // GET ALL pending tickets
    @GET
    @Path("/tickets/open")
    Response getAllPendingTickets(@HeaderParam("X-API-KEY") String apiKey);

    // Moved from messages interactions to tickets
    // GET tickets Id with user messages by user id
    @GET
    @Path("/tickets/interactions/users/{id}")
    Response getTicketsIdsWithUserMessage(@HeaderParam("X-API-KEY") String apiKey, @PathParam("id") String id);

    // DELETE ticket
    @DELETE
    @Path("/{id}")
    public Response deleteTicketById(@HeaderParam("X-API-KEY") String apiKey, @PathParam("id") String id);


    // MESSAGE

    // CREATE message
    @POST
    @Path("/messages/tickets/{id}")
    @Consumes("application/json")
    Response createMessage(@HeaderParam("X-API-KEY") String apiKey, SaveMessageRequest messagePayload);

    // UPDATE message
    @PUT
    @Path("/messages/{id}")
    @Consumes("application/json")
    Response updateMessage(@HeaderParam("X-API-KEY") String apiKey, String updatedMessagePayload);

    // DELETE message
    @DELETE
    @Path("/{id}")
    public Response deleteMessageById(@HeaderParam("X-API-KEY") String apiKey, @PathParam("id") String id);

    // GET message by id
    @GET
    @Path("/messages/{id}")
    Response getMessageByid(@HeaderParam("X-API-KEY") String apiKey, @PathParam("id") String id);

    // GET ALL messages by ticket id
    @GET
    @Path("/messages/tickets/{id}")
    Response getMessagesByTicketId(@HeaderParam("X-API-KEY") String apiKey, @PathParam("id") String id);

}