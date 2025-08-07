package org.stll.Services;

import jakarta.inject.Inject;
import jakarta.transaction.Transactional;
import org.stll.Entities.Ticket;
import org.stll.Repositories.TicketRepository;

import java.util.List;
import java.util.Optional;

public class TicketService {

    @Inject
    TicketRepository ticketRepository;

    // CREATE ticket
    @Transactional
    public Ticket createTicket(Ticket ticket) {
        return ticketRepository.save(ticket);
    }

    // FIND all tickets created by USER id
    @Transactional
    public List<Ticket> findTicketsByUserId(int userId) {
        return ticketRepository.findAllTicketsByUserId(userId);
    }

    // FIND all open tickets
    @Transactional
    public List<Ticket> findAllOpenTickets() {
        return ticketRepository.findAllOpenTickets();
    }

    // FIND one ticket by Ticket id
    public Optional<Ticket> findTicketById(int ticketId) {
        return ticketRepository.findById(ticketId);
    }

    // FIND one ticket creator by ticket id
    public Optional<Integer> findTicketCreatorId(int ticketId) {
        return ticketRepository.findUserIdByTicketId(ticketId);
    }

    // UPDATE ticket
    public Optional<Ticket> updateTicket(Ticket ticket) {
        return Optional.of(ticketRepository.update(ticket));
    }

    // DELETE ticket
    public boolean delete(int ticketId) {
        return ticketRepository.deleteById(ticketId);
    }
}
