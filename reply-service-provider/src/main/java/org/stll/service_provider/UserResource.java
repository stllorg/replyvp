package org.stll.service_provider;

import jakarta.annotation.security.RolesAllowed;
import jakarta.inject.Inject;
import jakarta.ws.rs.*;
import jakarta.ws.rs.core.MediaType;
import jakarta.ws.rs.core.Response;
import org.eclipse.microprofile.config.inject.ConfigProperty;
import org.eclipse.microprofile.jwt.JsonWebToken;
import org.eclipse.microprofile.rest.client.inject.RestClient;
import org.stll.service_provider.dtos.SaveUserRequest;

@Path("/api/v1/users")
@Produces(MediaType.APPLICATION_JSON)
@Consumes(MediaType.APPLICATION_JSON)
public class UserResource {

    @Inject
    @RestClient
    BackendRestClient backendRestClient;

    @ConfigProperty(name = "your-api.security.api-key")
    String apiKey;

    @Inject
    JsonWebToken jwt;

    @GET
    @Path("/{id}")
    @RolesAllowed({"admin", "user", "support"})
    public Response getUserById(@PathParam("id") String id) {
        return backendRestClient.getUserById(apiKey, id);
    }

    @POST
    @RolesAllowed("admin")
    public Response createUser(SaveUserRequest userPayload) {
        return backendRestClient.createUser(apiKey, userPayload);
    }

    // Update ticket by Id
    @PUT
    @Path("/{id}")
    @RolesAllowed({"admin", "user", "support"})
    public Response updateUser(@PathParam("id") String id, String updatedUserPayload) {

        boolean isAdmin = jwt.getGroups().contains("admin");
        String currentUserId = jwt.getSubject();

        if (!isAdmin && !currentUserId.equals(id)) {
            return Response.status(Response.Status.FORBIDDEN)
                    .entity("Only the account user or admin can update this user.")
                    .build();
        }

        return backendRestClient.updateUser(apiKey, updatedUserPayload);
    }

    @DELETE
    @Path("/{id}")
    @RolesAllowed({"admin", "user", "support"})
    public Response deleteUserById(@PathParam("id") String id) {
        return backendRestClient.deleteUserById(apiKey, id);
    }

}