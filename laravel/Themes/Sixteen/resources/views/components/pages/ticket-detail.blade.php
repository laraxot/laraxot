{{--
    Dettaglio pubblico /{lang}/tickets/{id} (DCP-148). I dati ($ticketDetail) li prepara pages/[container0]/[slug0]/index.blade.php con
    BuildPublicTicketDetailAction (404 se la segnalazione non esiste o non e' pubblica); il contenuto sta nel blocco Design Comuni.
--}}
@include('pub_theme::components.blocks.ticket-detail.design-comuni', ['detail' => $ticketDetail])
