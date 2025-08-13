package org.stll.Services;

import io.smallrye.jwt.build.Jwt;
import jakarta.enterprise.context.ApplicationScoped;
import jakarta.inject.Inject;
import jakarta.persistence.EntityExistsException;
import lombok.extern.jbosslog.JBossLog;
import org.eclipse.microprofile.config.inject.ConfigProperty;
import org.stll.Entities.User;
import org.stll.dtos.LoginRequest;
import org.stll.utils.PasswordEncoder;

import java.io.IOException;
import java.io.InputStream;
import java.util.Arrays;
import java.util.Optional;
import java.util.Set;
import java.util.stream.Collectors;

@ApplicationScoped
@JBossLog
public class AuthService {

    @Inject
    private UserService userService;

    @Inject
    private PasswordEncoder passwordEncoder;

    @ConfigProperty(name = "mp.jwt.sign.key.location")
    String privateKeyLocation;

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
        log.info("AuthService Trying to login username: " + request.getUsername() );
        Optional<User> userOptional = userService.findUserByUsername(request.getUsername());

        if (userOptional.isEmpty()) {
            log.info("AuthService User not found!");
            return Optional.empty();
        }

        if (!passwordEncoder.checkPassword(request.getPassword(), userOptional.get().getPassword())) {
            log.info("AuthService User password is wrong! ");
            return Optional.empty();
        }

        User user = userOptional.get();
        log.info("AuthService User found" + user.getUsername());
        return generateToken(user);
    }

    private Optional<String> generateToken(User user) {
        log.info("AuthService : Trying to generate JWT token" );
        String filePath = privateKeyLocation.replace("file:", "");

        log.info("AuthService: The private key Path " + filePath);
        try {
            InputStream is = Thread.currentThread().getContextClassLoader().getResourceAsStream(filePath);
            if (is == null) {
                throw new IOException("Resource not found: " + filePath);
            }
            String privateKey = new String(is.readAllBytes());

            log.info("AuthService: The private key was found");

            log.info("AuthService: Trying to get roles information");


            String[] roles = userService.getUserRolesByUserId(user.getId());
            Set<String> rolesSet = Arrays.stream(roles).collect(Collectors.toSet());

            log.info("AuthService: Trying to genereate a new signed JWT Token");

            String token = Jwt.issuer("replyvp")
                    .upn(user.getUsername())
                    .groups(rolesSet)
                    .claim("id", user.getId())
                    .expiresIn(3600)
                    .sign(privateKey);

            log.info("AuthService The JWT token was generated for user: " + user.getUsername());
            return Optional.of(token);
        } catch (IOException e) {
            log.fatal("Failed while trying to read private key for JWT signing with path " + filePath + "Exception: " + e.getMessage());
            return Optional.empty();
        } catch (Exception e) {
            log.fatal("Failed while trying to generate JWT token: " + e.getMessage());
            return Optional.empty();
        }
    }
}