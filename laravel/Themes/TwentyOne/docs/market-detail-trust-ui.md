# TwentyOne Market Detail Trust UI

## Scopo

Allineare la pagina dettaglio mercato del tema `TwentyOne` ai requisiti minimi di fiducia di un prediction market moderno.

## Gap corretto

Prima la pagina conteneva:

- titolo hardcoded non coerente col record
- copy statico non pertinente nel blocco `market_rules_tags`
- nessuna esposizione chiara di fonte primaria, fallback e regola di cancellazione

## Correzione applicata

- titolo reale del record: 100%
- contratto di risoluzione visibile: 100%
- badge stato mercato visibile: 100%
- timeline operativa visibile: 100%
- tempi di chiusura/risoluzione visibili: 100%
- tag reali del record: 100% quando disponibili

## Dati usati

- `title`
- `description` / `excerpt`
- `resolution_source`
- `fallback_source`
- `cancellation_rule`
- `closed_at`
- `resolved_at`
- `is_bettable`
- `tags`
- `count_credit`
- `sum_credit`
- `liquidity`

## Gap residuo

- trade recency: 0%
- spread: 0%
- depth/order book quality: 0%
- evidence/dispute history: 0%

## Regola UI

- niente placeholder statici in pagina mercato
- il contratto di risoluzione deve essere leggibile in meno di 10 secondi
- prima trust e stato, poi superfici piu' avanzate
