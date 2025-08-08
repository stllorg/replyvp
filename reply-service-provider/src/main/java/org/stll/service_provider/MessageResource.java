package org.stll.service_provider;

import jakarta.annotation.security.RolesAllowed;
import jakarta.inject.Inject;
import jakarta.ws.rs.*;
import jakarta.ws.rs.core.Response;
import org.eclipse.microprofile.config.inject.ConfigProperty;
import org.eclipse.microprofile.rest.client.inject.RestClient;

public class MessageResource {

    @Inject
    @RestClient
    BackendRestClient backendRestClient;

    @ConfigProperty(name = "your-api.security.api-key")
    String apiKey;

    // Get message by Id
    @GET
    @Path("/{id}")
    @RolesAllowed({"admin", "user", "support"})
    public Response getMessageById(@PathParam("id") String id) {
        return backendRestClient.getMessageByid(apiKey, id);
    }

    // GET messages by ticket Id
    @GET
    @Path("/{id}")
    @RolesAllowed({"admin", "user", "support"})
    public Response getMessagesByTicketId(@PathParam("id") String id) {
        return backendRestClient.getMessagesByTicketId(apiKey, id);
    }

    // Create message
    @POST
    @RolesAllowed("user")
    public Response createMessage(String messagePayload) {
        return backendRestClient.createMessage(apiKey, messagePayload);
    }

    // Update message by Id
    @PUT
    @Path("/{id}")
    @RolesAllowed({"admin", "user", "support"})
    public Response updateMessage(@PathParam("id") String id,  String messagePayload) {
        return backendRestClient.updateMessage(apiKey, messagePayload);
    }

    // Delete message
    @DELETE
    @Path("/{id}")
    @RolesAllowed({"admin", "user", "support"})
    public Response deleteMessageById(@PathParam("id") String id) {
        return backendRestClient.deleteMessageById(apiKey, id);
    }
}
