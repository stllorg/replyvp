package org.stll.Entities;

import jakarta.persistence.*;
import lombok.*;

import java.time.LocalDateTime;

@Entity
@Table(name= "users")
@Data
@NoArgsConstructor
@AllArgsConstructor
@EqualsAndHashCode(callSuper = false)
public class User {
    private int id;
    private String username;
    private String email;
    private String password;
    private LocalDateTime createdAt;

    public User(String username, String email, String hashedPassword) {
    }
}
