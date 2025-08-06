package org.stll.Services;

import io.quarkus.hibernate.orm.panache.PanacheQuery;
import io.quarkus.panache.common.Page;
import io.quarkus.panache.common.Sort;
import jakarta.enterprise.context.ApplicationScoped;
import jakarta.transaction.Transactional;
import org.stll.Entities.Role;
import org.stll.Entities.User;
import org.stll.dtos.PaginationResponse;
import org.stll.dtos.UserDTO;

import java.util.List;
import java.util.Optional;
import java.util.stream.Collectors;

@ApplicationScoped
public class UserService {

    @Transactional
    public User createUser(User user) {
        user.persist();

        // ROLE 4 = USER
        Optional<Role> defaultRole = Role.findByIdOptional(4L);

        if (defaultRole.isPresent()) {
            user.getRoles().add(defaultRole.get());
            user.persistAndFlush(); // Persiste as mudanças na relação de roles
        } else {
           throw new RuntimeException("Default role not found.");
        }
        return user;
    }

    @Transactional
    public boolean insertUserRole(Long userId, Long roleId) {
        Optional<User> userOptional = User.findByIdOptional(userId);
        Optional<Role> roleOptional = Role.findByIdOptional(roleId);

        if (userOptional.isPresent() && roleOptional.isPresent()) {
            User user = userOptional.get();
            Role role = roleOptional.get();
            if (user.getRoles().add(role)) {
                user.persistAndFlush();
                return true;
            }
        }
        return false;
    }

    @Transactional
    public boolean deleteAllUserRoles(Long userId) {
        Optional<User> userOptional = User.findByIdOptional(userId);
        if (userOptional.isPresent()) {
            User user = userOptional.get();
            user.getRoles().clear();
            user.persistAndFlush();
            return true;
        }
        return false;
    }

    public PaginationResponse<UserDTO> findUsers(int page, int limit) {
        PanacheQuery<User> query = User.findAll(Sort.by("id"));

        List<User> users = query.page(Page.of(page - 1, limit)).list(); // page é zero-based

        List<UserDTO> userDtos = users.stream()
                .map(user -> new UserDTO(user.id, user.getUsername(), user.getEmail()))
                .collect(Collectors.toList());

        long totalUsers = User.count();
        int totalPages = (int) Math.ceil((double) totalUsers / limit);

        PaginationResponse.PaginationInfo paginationInfo = new PaginationResponse.PaginationInfo(
                page, limit, totalUsers, totalPages
        );

        return new PaginationResponse<>(userDtos, paginationInfo);
    }


    public Optional<User> getUserByEmail(String email) {
        return User.findByEmail(email);
    }

    public Optional<User> getUserByUsername(String username) {
        return User.findByUsername(username);
    }

    public Optional<User> getUserById(Long id) {
        return User.findByIdOptional(id);
    }

    public String[] getUserRolesByUserId(Long userId) {
        Optional<User> userOptional = User.findByIdOptional(userId);
        if (userOptional.isPresent()) {
            return userOptional.get().getRoles().stream()
                    .map(Role::getName)
                    .toArray(String[]::new);
        }
        return new String[]{};
    }

}
