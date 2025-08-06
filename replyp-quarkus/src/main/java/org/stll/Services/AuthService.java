package org.stll.Services;

import io.smallrye.jwt.build.Jwt;
import jakarta.enterprise.context.ApplicationScoped;
import jakarta.inject.Inject;
import jakarta.persistence.EntityExistsException;
import org.eclipse.microprofile.jwt.Claims;
import org.stll.Entities.User;
import org.stll.dtos.LoginRequest;
import org.stll.utils.PasswordEncoder;

import java.util.Arrays;
import java.util.Optional;

@ApplicationScoped
public class AuthService {

    @Inject
    private UserService userService;

    @Inject
    private PasswordEncoder passwordEncoder;

    private String jwtSecret;

    public User register(String username, String password, String email) {
        if (userService.getUserByEmail(email).isPresent()) {
            throw new EntityExistsException();
        }

        String hashedPassword = passwordEncoder.hashPassword(password);

        User user = new User(username, email, hashedPassword);
        userService.createUser(user);

        return user;
    }

    public Optional<String> loginWithEmail(LoginRequest request) {
        // request.username is as parameter to represent user.email
        Optional<User> userOptional = userService.getUserByEmail(request.getUsername());

        if (userOptional.isEmpty() || !passwordEncoder.checkPassword(request.getPassword(), userOptional.get().getPassword())) {
            return Optional.empty();
        }

        User user = userOptional.get();
        return generateToken(user);
    }

    public Optional<String> loginWithUsername(LoginRequest request) {
        Optional<User> userOptional = userService.getUserByEmail(request.getUsername());

        if (userOptional.isEmpty() || !passwordEncoder.checkPassword(request.getPassword(), userOptional.get().getPassword())) {
            return Optional.empty();
        }

        User user = userOptional.get();
        return generateToken(user);
    }

    private Optional<String> generateToken(User user) {
        String[] roles = userService.getUserRolesByUserId(user.id);

        String token = Jwt.issuer("stllorg/issuer")
                .upn(user.getUsername())
                .groups(Arrays.toString(roles))
                .claim(Claims.email, user.getEmail())
                .expiresIn(3600)
                .sign();

        return Optional.of(token);
    }
}