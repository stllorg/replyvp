package org.stll.Services;

import jakarta.inject.Inject;
import jakarta.transaction.Transactional;
import org.stll.Entities.Message;
import org.stll.Repositories.MessageRepository;

import java.util.List;

public class MessageService {

    @Inject
    MessageRepository messageRepository;

    // CREATE message
    @Transactional
    public Message createMessage(Message message) {
        return messageRepository.save(message);
    }

    // FIND ALL messages by ticket id
    public List<Message> getMessagesByTicketId(int ticketId) {
        return messageRepository.findAllMessagesByTicketId(ticketId);
    }

    // FIND ALL tickets Ids with messages by user
    public List<Integer> getTicketIdsWithUserMessagesByUserId(int userId) {
        return messageRepository.findAllTicketIdWithUserMessages(userId);
    }
}
