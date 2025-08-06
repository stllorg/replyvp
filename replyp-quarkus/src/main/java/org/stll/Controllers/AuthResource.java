package org.stll.Controllers;

import jakarta.annotation.security.PermitAll;
import jakarta.inject.Inject;
import jakarta.ws.rs.Consumes;
import jakarta.ws.rs.POST;
import jakarta.ws.rs.Path;
import jakarta.ws.rs.Produces;
import jakarta.ws.rs.core.MediaType;
import jakarta.ws.rs.core.Response;
import org.stll.dtos.LoginRequest;
import org.stll.dtos.RegistrationRequest;
import org.stll.Services.AuthService;
import io.smallrye.jwt.build.Jwt;

import java.util.HashSet;
import java.util.Set;


@Path("/auth")
@Produces(MediaType.APPLICATION_JSON)
@Consumes(MediaType.APPLICATION_JSON)
public class AuthResource {

    @Inject
    AuthService authService;
    // Registration Endpoint was POST /users.
    @POST
    @Path("/register")
    @PermitAll
    public Response register(RegistrationRequest request) {
        try {
            authService.register(request.getUsername(), request.getEmail(), request.getPassword());
            return Response.ok("User registered successfully").build();
        } catch (RuntimeException e) {
            return Response.status(Response.Status.BAD_REQUEST).entity(e.getMessage()).build();
        }
    }

    @POST
    @Path("/login")
    @PermitAll
    public Response login(LoginRequest request) {
        try {
            authService.loginWithUsername(request);
            
            Set<String> roles = new HashSet<>();
            roles.add("USER");

            String token = Jwt.issuer("stllorg/issuer")
                    .subject(request.getUsername())
                    .upn(request.getUsername())
                    .groups(roles)
                    .expiresIn(24 * 60 * 60)
                    .sign();

            return Response.ok().entity("{\"token\": \"" + token + "\"}").build();
        } catch (RuntimeException e) {
            return Response.status(Response.Status.UNAUTHORIZED).entity(e.getMessage()).build();
        }
    }
}