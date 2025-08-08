package org.stll.Services;

import jakarta.enterprise.context.ApplicationScoped;
import jakarta.inject.Inject;
import jakarta.transaction.Transactional;
import org.stll.Entities.Message;
import org.stll.Entities.Ticket;
import org.stll.Repositories.MessageRepository;

import java.util.List;
import java.util.Optional;

@ApplicationScoped
public class MessageService {

    @Inject
    MessageRepository messageRepository;

    // CREATE message
    @Transactional
    public Message createMessage(Message message) {
        return messageRepository.save(message);
    }

    // FIND one message by Ticket id
    public Optional<Message> findMessageById(int messageId) {
        return messageRepository.findById(messageId);
    }

    // FIND ALL messages by ticket id
    public List<Message> getMessagesByTicketId(int ticketId) {
        return messageRepository.findAllMessagesByTicketId(ticketId);
    }

    // DELETE message by Id
    public boolean delete(int id) {
        return messageRepository.deleteById(id);
    }
}
