# Fixcity Ticket Infolist Theme Boundary

## Scopo

Documentare il boundary tra modulo `Fixcity` e tema `Sixteen` per la nuova classe `TicketInfolist`.

## Regola

- Il modulo `Fixcity` possiede schema e business semantics dell'infolist.
- Il tema `Sixteen` non replica campi o logica infolist: applica solo presentazione (CSS/layout globale), senza duplicare struttura dati.

## Impatto pratico

- Con `TicketInfolist` dedicato, la pagina `ViewTicket` ottiene una struttura read-only stabile lato modulo.
- Sixteen resta allineato al principio "tema = vestito, modulo = logica".

## Riferimenti

- [ticketinfolist pattern reference](../../../../Modules/Fixcity/docs/wiki/concepts/ticketinfolist-pattern-reference.md)
- [theme css only parity rule](./theme-css-only-parity-rule.md)
- [sixteen best practices](./sixteen-best-practices.md)
