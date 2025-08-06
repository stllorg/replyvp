package org.stll.Entities;

import io.quarkus.hibernate.orm.panache.PanacheEntity;
import jakarta.persistence.Column;
import jakarta.persistence.Entity;
import jakarta.persistence.Table;
import lombok.Data;
import lombok.EqualsAndHashCode;
import lombok.NoArgsConstructor;

import java.util.Optional;

@Entity
@Table(name = "roles")
@Data
@NoArgsConstructor
@EqualsAndHashCode(callSuper = false)
public class Role extends PanacheEntity {
    @Column(unique = true, nullable = false)
    private String name;

    public Role(String name) {
        this.name = name;
    }

    public static Optional<Role> findByName(String name) {
        return find("name", name).firstResultOptional();
    }
}
