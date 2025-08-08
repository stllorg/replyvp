package org.stll.Services;

import io.quarkus.panache.common.Page;
import jakarta.enterprise.context.ApplicationScoped;
import jakarta.inject.Inject;
import jakarta.persistence.EntityExistsException;
import jakarta.transaction.Transactional;
import org.stll.Entities.Role;
import org.stll.Entities.User;
import org.stll.Repositories.UserRepository;
import org.stll.dtos.PaginationResponse;
import org.stll.utils.PasswordEncoder;

import java.util.List;
import java.util.Optional;

@ApplicationScoped
public class UserService {

    @Inject
    UserRepository userRepository;

    @Inject
    private PasswordEncoder passwordEncoder;

    public User createUserFromRequest(String username, String password, String email) {
        if (findUserByEmail(email).isPresent()) {
            throw new EntityExistsException();
        }
        String hashedPassword = passwordEncoder.hashPassword(password);

        User user = new User(username, email, hashedPassword);
        createUser(user);

        return user;
    }

    @Transactional
    public User createUser(User user) {
        // Validate if username or email already exists
        if (userRepository.findByUsername(user.getUsername()).isPresent()) {
            throw new IllegalArgumentException("Username already exists: " + user.getUsername());
        }
        if (userRepository.findByEmail(user.getEmail()).isPresent()) {
            throw new IllegalArgumentException("Email already exists: " + user.getEmail());
        }

        // Save the user
        User savedUser = userRepository.save(user);

        // Assign the default role (ID 4) to the new user
        userRepository.assignRoleToUser(savedUser.getId(), 4);

        return savedUser;
    }

    public Optional<User> findUserByEmail(String email) {
        return userRepository.findByEmail(email);
    }

    public Optional<User> findUserByUsername(String username) {
        return userRepository.findByUsername(username);
    }

    public Optional<User> findUserById(int id) {
        return userRepository.findById(id);
    }

    public PaginationResponse<User> getUsers(int page, int limit) {
        return userRepository.findAll(page, limit);
    }

    public boolean delete(int id) {
        return userRepository.deleteById(id);
    }

    public String[] getUserRolesByUserId(int userId) {
        Optional<User> userOptional = userRepository.findById(userId);
        if (userOptional.isPresent()) {
            return userRepository.findRolesByUserId(userId).stream()
                    .map(Role::getName)
                    .toArray(String[]::new);
        }
        return new String[]{};
    }

    @Transactional
    public boolean updateUserRoles(int userId, List<Integer> rolesIdsList) {
        Optional<User> userOptional = userRepository.findById(userId);

        if (userOptional.isPresent()) {
            userRepository.updateUserRoles(userId, rolesIdsList);
        }
        return false;
    }

    @Transactional
    public boolean insertUserRole(int userId, int roleId) {
        Optional<User> userOptional = userRepository.findById(userId);

        if (userOptional.isPresent()) {
            userRepository.assignRoleToUser(userId, roleId);
        }
        return false;
    }

    @Transactional
    public boolean deleteAllUserRoles(int userId) {
        Optional<User> userOptional = userRepository.findById(userId);
        if (userOptional.isPresent()) {
            userRepository.deleteRolesByUserId(userId);
            return true;
        }
        return false;
    }
}
