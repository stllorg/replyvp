package org.stll.service_provider;// src/main/java/com/yourcompany/apigateway/AuthResource.java

import io.smallrye.jwt.build.Jwt;
import jakarta.ws.rs.Consumes;
import jakarta.ws.rs.Path;
import jakarta.ws.rs.Produces;
import org.eclipse.microprofile.config.inject.ConfigProperty;
import org.eclipse.microprofile.jwt.JsonWebToken;
import org.eclipse.microprofile.rest.client.inject.RestClient;
import jakarta.annotation.security.PermitAll;
import jakarta.annotation.security.RolesAllowed;
import jakarta.inject.Inject;
import jakarta.ws.rs.*;
import jakarta.ws.rs.core.MediaType;
import jakarta.ws.rs.core.Response;
import org.stll.service_provider.dtos.LoginRequest;

import java.util.HashSet;
import java.util.Set;

@Path("/auth")
@Produces(MediaType.APPLICATION_JSON)
@Consumes(MediaType.APPLICATION_JSON)
public class AuthResource {

    @Inject
    @RestClient
    BackendRestClient backendRestClient;

    @ConfigProperty(name = "your-api.security.api-key")
    String apiKey;

    @Inject
    JsonWebToken jwt;

    // 1. Endpoint de Login
    @POST
    @Path("/login")
    @PermitAll
    public Response login(LoginRequest loginRequest) {
        String loginPayload = String.format("{\"username\":\"%s\", \"password\":\"%s\"}", 
                                           loginRequest.getUsername(),
                                           loginRequest.getPassword());

        Response principalApiResponse = backendRestClient.login(apiKey, loginPayload);

        if (principalApiResponse.getStatus() == Response.Status.OK.getStatusCode()) {

            // TODO: get roles from database
            Set<String> userRoles = Set.of();
            String token = generateJwt(loginRequest.getUsername(), userRoles);
            return Response.ok().entity("{\"token\":\"" + token + "\"}").build();
        } else {
            return Response.status(Response.Status.UNAUTHORIZED)
                           .entity(principalApiResponse.readEntity(String.class))
                           .build();
        }
    }

    @GET
    @Path("/authenticate")
    @RolesAllowed({"admin", "user", "support"})
    public Response validateToken() {
        
        String userId = jwt.getSubject();
        Set<String> groups = jwt.getGroups();

        return Response.ok()
                       .entity(String.format("{\"userId\":\"%s\", \"groups\":%s}", userId, groups))
                       .build();
    }

    @POST
    @Path("/register")
    @PermitAll
    public Response register(RegisterRequest registerRequest) {
        String registerPayload = String.format("{\"username\":\"%s\", \"email\":\"%s\", \"password\":\"%s\"}",
                                               registerRequest.username,
                                               registerRequest.email,
                                               registerRequest.password);

        Response principalApiResponse = backendRestClient.register(apiKey, registerPayload);

        if (principalApiResponse.getStatus() == Response.Status.CREATED.getStatusCode()) {
            return Response.status(Response.Status.CREATED)
                           .entity(principalApiResponse.readEntity(String.class))
                           .build();
        } else {
            return Response.status(principalApiResponse.getStatus())
                           .entity(principalApiResponse.readEntity(String.class))
                           .build();
        }
    }

    private String generateJwt(String username, Set<String> roles) {
        return Jwt.issuer("https://your-api-gateway.com")
                  .upn(username)
                  .groups(roles)
                  .expiresIn(3600)
                  .sign();
    }



    public static class RegisterRequest {
        public String username;
        public String email;
        public String password;
    }
}