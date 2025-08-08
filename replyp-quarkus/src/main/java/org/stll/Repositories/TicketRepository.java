package org.stll.Repositories;

import jakarta.enterprise.context.ApplicationScoped;
import jakarta.inject.Inject;
import jakarta.persistence.EntityManager;
import jakarta.persistence.NoResultException;
import jakarta.transaction.Transactional;
import org.stll.Entities.Ticket;

import java.util.Collections;
import java.util.List;
import java.util.Optional;

@ApplicationScoped
public class TicketRepository {

    @Inject
    EntityManager em;

    public Ticket save(Ticket ticket) {
        em.createNativeQuery(
                        "INSERT INTO tickets (subject, status, user_id) VALUES (?, ?, ?)"
                )
                .setParameter(1, ticket.getSubject())
                .setParameter(2, ticket.getStatus())
                .setParameter(3, ticket.getUserId())
                .executeUpdate();

        // After insertion, retrieve the ID from the saved Ticket.
        Optional<Ticket> lastSavedTicket = findLastTicketByUserId(ticket.getUserId());

        return lastSavedTicket.get();
    }

    public List<Ticket> findAllTicketsByUserId(int userId) {

        List<Ticket> tickets;

        try {
            tickets = em.createNativeQuery(
                        "SELECT id, subject, status, created_at FROM tickets WHERE user_id = ?"
                )
                .setParameter(1, userId)
                .getResultList();

        } catch (jakarta.persistence.NoResultException e) {
            tickets = Collections.emptyList();
        }

        return tickets;
    }

    public List<Ticket> findAllOpenTickets() {

        List<Ticket> tickets;

        try {
            tickets = em.createNativeQuery(
                            "SELECT t.id, t.subject, created_at FROM tickets t JOIN users u ON t.user_id = u.id WHERE t.status = 'open' "
                    )
                    .getResultList();

        } catch (jakarta.persistence.NoResultException e) {
            tickets = Collections.emptyList();
        }

        return tickets;
    }

    public Optional<Ticket> findLastTicketByUserId(int userId) {

        List<Ticket> tickets;

        try {
            tickets = em.createNativeQuery(
                            "SELECT id FROM tickets WHERE user_id = ? ORDER BY created_at DESC"
                    )
                    .setParameter(1, userId)
                    .setMaxResults(1)
                    .getResultList();

            return Optional.of(tickets.getFirst());
        } catch (jakarta.persistence.NoResultException e) {
            return Optional.empty();
        }
    }

    public Optional<Ticket> findById(int ticketId) {
        try {
            Ticket ticket = (Ticket) em.createNativeQuery(
                    "SELECT id, subject, status, created_at FROM tickets WHERE id = ?", Ticket.class
            ).setParameter(1, ticketId)
                    .getSingleResult();

            return Optional.of(ticket);

        } catch (NoResultException e) {
            return Optional.empty();
        }
    }

    public Optional<Integer> findUserIdByTicketId(int ticketId) {
        try {
            Ticket ticket = (Ticket) em.createNativeQuery(
                            "SELECT id, userId FROM tickets WHERE id = ?", Ticket.class
                    ).setParameter(1, ticketId)
                    .getSingleResult();

            // Return user id
            return Optional.of(ticket.getUserId());

        } catch (NoResultException e) {
            return Optional.empty();
        }
    }

    @Transactional
    public Ticket update(Ticket ticket) {
        int rowsAffected = em.createNativeQuery(
                        "UPDATE tickets SET subject = ?, status = ? WHERE id = ?"
                )
                .setParameter(1, ticket.getSubject())
                .setParameter(2, ticket.getStatus())
                .executeUpdate();

        return em.find(Ticket.class, ticket.getId());
    }

    public List<Integer> findAllTicketIdWithUserMessages(int ticketId) {

        List<Integer> ticketsIds;

        try {
            ticketsIds = em.createNativeQuery(
                            "SELECT DISTINCT ticket_id FROM ticket_messages WHERE user_id = ?"
                    )
                    .setParameter(1, ticketId)
                    .getResultList();

        } catch (jakarta.persistence.NoResultException e) {
            ticketsIds = Collections.emptyList();
        }

        return ticketsIds;
    }

    @Transactional
    public boolean deleteById(int id) {
        int rowsAffected = em.createNativeQuery("DELETE FROM tickets WHERE id = ?")
                .setParameter(1, id)
                .executeUpdate();

        return rowsAffected > 0;
    }
}
