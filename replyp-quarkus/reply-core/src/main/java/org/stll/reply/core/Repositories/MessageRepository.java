package org.stll.reply.core.Repositories;

import jakarta.enterprise.context.ApplicationScoped;
import jakarta.inject.Inject;
import jakarta.persistence.EntityManager;
import jakarta.persistence.NoResultException;
import jakarta.transaction.Transactional;
import org.stll.reply.core.Entities.Message;

import java.util.Collections;
import java.util.List;
import java.util.Optional;

@ApplicationScoped
public class MessageRepository {

    @Inject
    EntityManager em;

    public Message save(Message message) {
        em.createNativeQuery(
                        "INSERT INTO ticket_messages (ticket_id, message, user_id) VALUES (?, ?, ?)"
                )
                .setParameter(1, message.getTicketId())
                .setParameter(2, message.getMessage())
                .setParameter(3, message.getUserId())
                .executeUpdate();

        // After insertion, retrieve the ID from the saved Ticket.
        Optional<Message> lastSavedMessage = findLastMessageByUserId(message.getUserId());

        return lastSavedMessage.get();
    }

    public Optional<Message> findById(int messageId) {
        try {
            Message message = (Message) em.createNativeQuery(
                            "SELECT id, ticket_id, message, created_at, user_id FROM ticket_messages WHERE id = ?", Message.class
                    ).setParameter(1, messageId)
                    .getSingleResult();

            return Optional.of(message);

        } catch (NoResultException e) {
            return Optional.empty();
        }
    }

    public List<Message> findAllMessagesByTicketId(int ticketId) {
        List<Message> messages;

        try {
            messages = em.createNativeQuery(
                            "SELECT id, ticket_id, message, created_at, user_id FROM ticket_messages WHERE ticket_id = ?"
                    )
                    .setParameter(1, ticketId)
                    .getResultList();

        } catch (jakarta.persistence.NoResultException e) {
            messages = Collections.emptyList();
        }

        return messages;
    }

    public Optional<Message> findLastMessageByUserId(int userId) {

        List<Message> userMessages;

        try {
            userMessages = em.createNativeQuery(
                            "SELECT id FROM ticket_messages WHERE user_id = ? ORDER BY created_at DESC"
                    )
                    .setParameter(1, userId)
                    .setMaxResults(1)
                    .getResultList();

            return Optional.of(userMessages.getFirst());
        } catch (jakarta.persistence.NoResultException e) {
            return Optional.empty();
        }
    }

    @Transactional
    public boolean deleteById(int id) {
        int rowsAffected = em.createNativeQuery("DELETE FROM ticket_messages WHERE id = ?")
                .setParameter(1, id)
                .executeUpdate();

        return rowsAffected > 0;
    }
}
