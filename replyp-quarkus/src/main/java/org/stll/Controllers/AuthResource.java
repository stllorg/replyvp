package org.stll.Controllers;

import jakarta.annotation.security.PermitAll;
import jakarta.annotation.security.RolesAllowed;
import jakarta.inject.Inject;
import jakarta.validation.Valid;
import jakarta.ws.rs.*;
import jakarta.ws.rs.core.MediaType;
import jakarta.ws.rs.core.Response;
import org.eclipse.microprofile.jwt.JsonWebToken;
import org.stll.dtos.LoginRequest;
import org.stll.dtos.LoginResponse;
import org.stll.dtos.RegistrationRequest;
import org.stll.Services.AuthService;
import org.stll.dtos.ValidationResponse;

import java.util.Optional;
import java.util.Set;

@Path("/auth")
@Produces(MediaType.APPLICATION_JSON)
@Consumes(MediaType.APPLICATION_JSON)
public class AuthResource {

    @Inject
    AuthService authService;

    @Inject
    JsonWebToken jwt;

    // Registration Endpoint was POST /users.
    @POST
    @Path("/register")
    @PermitAll
    public Response register(@Valid RegistrationRequest request) {
        try {
            authService.register(request.getUsername(), request.getEmail(), request.getPassword());
            return Response.ok("User registered successfully").build();
        } catch (RuntimeException e) {
            return Response.status(Response.Status.BAD_REQUEST).entity(e.getMessage()).build();
        }
    }

    // JWT TOKEN VALIDATION
    @GET
    @Path("/authenticate")
    @RolesAllowed({"user", "admin"})
    @Produces(MediaType.APPLICATION_JSON)
    public Response validateToken() {
        String userId = jwt.getClaim("id").toString();
        Set<String> roles = jwt.getGroups();

        return Response.ok().entity(new ValidationResponse(userId, roles)).build();
    }

    // LOGIN
    @POST
    @Path("/login")
    @PermitAll
    public Response login(@Valid LoginRequest request) {
        try {
            Optional<String> token = authService.loginWithUsername(request);

            if (token.isPresent()) {
                return Response.ok().entity(new LoginResponse(true, "Login successful", token.get())).build();
            } else {
                throw new IllegalArgumentException("Invalid credentials");
            }
        } catch (RuntimeException e) {
            return Response.status(Response.Status.UNAUTHORIZED).entity(e.getMessage()).build();
        }
    }
}