---
title: "area-personale/pratiche — perché è un placeholder"
type: incident
tags: [folio, homepage, routing, incident]
created: 2026-07-16
---

# area-personale/pratiche resta un placeholder (2026-07-16)

## Cosa è successo

Un commit ha sostituito il placeholder di questa pagina con codice copiato
da un progetto gemello ("Fixcity"): `use Modules\Fixcity\Actions\BuildAuthenticatedUserTicketsQueryAction;`
e un rendering di `$tickets` (nome, contenuto, stato). Nessun `Ticket` model,
nessuna Action equivalente esistono in questo repo (TechPlanner) — il modulo
`Fixcity` stesso non esiste qui, è un tenant/progetto diverso (`fixcity_data`
è un database MySQL separato, non parte di questa app).

## Perché ha rotto l'intero sito, non solo questa pagina

Laravel Folio, per estrarre `name()`/`middleware()` da ogni pagina, valuta
il PHP top-level di **ogni** file pagina durante la fase di route discovery —
non solo quando la pagina viene effettivamente richiesta. Una `app(ClasseInesistente::class)`
a livello di file, fuori da qualunque contesto lazy, quindi rompe il routing
di **tutte** le pagine, homepage inclusa: `folio:list` e ogni richiesta HTTP
fallivano con `BindingResolutionException`, non solo `/area-personale/pratiche`.

## Fix applicato

Ripristinato il placeholder originale (`git show <commit-precedente>:<path>`,
mai `git checkout`/`revert` — questa repo va solo in avanti). La feature reale
("le mie pratiche/segnalazioni") va reimplementata da zero per TechPlanner:
serve un `Ticket`-equivalente (model + migration) e un'Action `Modules\TechPlanner\Actions\...`
dedicata, non un import copiato da un altro progetto.

## Regola pratica

Prima di adattare codice da un progetto gemello, verificare che le classi
referenziate esistano **in questo repo**. Un `use` verso un namespace di
un altro tenant/progetto è un segnale di copia-incolla non completato.
Se una feature non ha ancora un model/Action reale, il placeholder onesto
("in arrivo") è meglio di codice che referenzia classi inesistenti — specie
in una pagina Folio, dove l'errore si propaga a tutto il sito.
