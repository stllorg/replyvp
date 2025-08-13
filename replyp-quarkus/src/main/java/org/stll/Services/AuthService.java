package org.stll.Services;

import io.smallrye.jwt.build.Jwt;
import jakarta.enterprise.context.ApplicationScoped;
import jakarta.inject.Inject;
import jakarta.persistence.EntityExistsException;
import org.eclipse.microprofile.config.inject.ConfigProperty;
import org.stll.Entities.User;
import org.stll.dtos.LoginRequest;
import org.stll.utils.PasswordEncoder;

import java.util.Arrays;
import java.util.Base64;
import java.util.Optional;
import java.util.Set;
import java.util.stream.Collectors;

@ApplicationScoped
public class AuthService {

    @Inject
    private UserService userService;

    @Inject
    private PasswordEncoder passwordEncoder;

    @ConfigProperty(name = "jwt.secret")
    String secret;


    public User register(String username, String password, String email) {
        if (userService.findUserByEmail(email).isPresent()) {
            throw new EntityExistsException();
        }
        String hashedPassword = passwordEncoder.hashPassword(password);

        User user = new User(username, email, hashedPassword);
        userService.createUser(user);

        return user;
    }

    public Optional<String> loginWithEmail(LoginRequest request) {
        // request.username is as parameter to represent user.email
        Optional<User> userOptional = userService.findUserByEmail(request.getUsername());

        if (userOptional.isEmpty() || !passwordEncoder.checkPassword(request.getPassword(), userOptional.get().getPassword())) {
            return Optional.empty();
        }

        User user = userOptional.get();
        return generateToken(user);
    }

    public Optional<String> loginWithUsername(LoginRequest request) {
        Optional<User> userOptional = userService.findUserByUsername(request.getUsername());

        if (userOptional.isEmpty() || !passwordEncoder.checkPassword(request.getPassword(), userOptional.get().getPassword())) {
            return Optional.empty();
        }

        User user = userOptional.get();
        return generateToken(user);
    }

    private Optional<String> generateToken(User user) {
        String[] roles = userService.getUserRolesByUserId(user.getId());
        Set<String> rolesSet = Arrays.stream(roles).collect(Collectors.toSet());

        String token = Jwt.issuer("replyvp")
                .upn(user.getUsername())
                .groups(rolesSet)
                .claim("id", user.getId())
                .expiresIn(3600)
                .signWithSecret(secret);

        return Optional.of(token);
    }
}