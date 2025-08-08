package org.stll.Controllers;

import jakarta.annotation.security.RolesAllowed;
import jakarta.inject.Inject;
import jakarta.ws.rs.*;
import jakarta.ws.rs.core.MediaType;
import jakarta.ws.rs.core.Response;
import org.eclipse.microprofile.jwt.JsonWebToken;
import org.stll.Entities.User;
import org.stll.Services.AuthService;
import org.stll.Services.UserService;
import org.stll.dtos.PaginationResponse;
import org.stll.dtos.RoleUpdateRequest;
import org.stll.utils.RolesConverter;

import java.util.Collections;
import java.util.List;
import java.util.Optional;
import java.util.Set;

@Path("/users")
@Produces(MediaType.APPLICATION_JSON)
@Consumes(MediaType.APPLICATION_JSON)
public class UserResource {

    @Inject
    UserService userService;

    @Inject
    RolesConverter rolesConverter;

    @Inject
    JsonWebToken jwt;

    @GET
    @RolesAllowed("admin")
    public Response fetchUsers(@QueryParam("page") @DefaultValue("1") int page,
                               @QueryParam("limit") @DefaultValue("15") int limit) {
        PaginationResponse<User> usersResult = userService.getUsers(page, limit);
        if (usersResult.getData().isEmpty()) {
            return Response.status(Response.Status.NOT_FOUND).entity(Collections.singletonMap("error", "Users not found")).build();
        }
        return Response.ok(usersResult).build();
    }

    @GET
    @Path("/{targetId}/roles")
    @RolesAllowed({"admin", "user"})
    public Response fetchUserRoles(@PathParam("targetId") int targetId) {
        int currentUserId = Integer.parseInt(jwt.getClaim("id").toString());
        Set<String> currentUserRoles = jwt.getGroups();
        if (currentUserId != targetId && !currentUserRoles.contains("admin")) {
            return Response.status(Response.Status.FORBIDDEN)
                    .entity(Collections.singletonMap("error", "You do not have permission to access this resource.")).build();
        }
        Optional<User> targetUser = userService.findUserById(targetId);
        if (targetUser.isEmpty()) {
            return Response.status(Response.Status.NOT_FOUND).entity(Collections.singletonMap("error", "User not found")).build();
        }
        List<String> roles = List.of(userService.getUserRolesByUserId(targetId));
        return Response.ok(Collections.singletonMap("roles", roles)).build();
    }

    @PUT
    @Path("/{targetId}/roles")
    @RolesAllowed("admin")
    public Response updateUserRole(@PathParam("targetId") int targetId, RoleUpdateRequest request) {
        if (request.getRoleNames() == null || request.getRoleNames().isEmpty()) {
            return Response.status(Response.Status.BAD_REQUEST).entity(Collections.singletonMap("error", "Missing roles value")).build();
        }
        try {
            Optional<User> targetUser = userService.findUserById(targetId);
            if (targetUser.isEmpty()) {
                return Response.status(Response.Status.NOT_FOUND).entity(Collections.singletonMap("error", "User not found")).build();
            }

            // Convert request.roles from names to roleIds
            List<Integer> roleIdsList = rolesConverter.execute(request.getRoleNames());

            // TODO: Update user roles
            userService.updateUserRoles(targetId, roleIdsList);

            return Response.noContent().build();
        } catch (Exception e) {
            return Response.status(Response.Status.BAD_REQUEST).entity(Collections.singletonMap("error", "Failed to update user roles")).build();
        }
    }
}
