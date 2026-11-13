---
title: "Frontoffice blog / events / tickets — verifica stato"
type: note
status: draft
tags: [blog, frontoffice, ponytail]
created: 2026-10-26
updated: 2026-10-26
---

# Stato FO — /it/blog, /it/events, /it/tickets

> Ponytail: riusa CMS JSON + blocco tema esistente; non inventare componenti nuovi.

## Verifica

- CMS JSON popolato (`blog/index.json`, `events/index.json`, `tickets/index.json`).
- Blocco tema esiste per blog (`pub_theme::components.blocks.blog.index`), events (`events-calendar`), tickets (`ticket-list`).
- Lock aperto su events (`events/index.json.lock`, DCP-289) — non toccare senza coordinamento.
- Server locale non raggiungibile (`192.168.1.40:8000`) durante sessione; verifica con `curl` dopo deploy.
- Docs tema: `Themes/Sixteen/docs/wiki/frontoffice-blog-events-tickets.md` (SSoT).

## Prossimo (non fatto — documentato)

- Se DB vuoto (`Article`/`Event`): il blocco mostrerà "Non ci sono ancora novità pubblicate"; popolare il DB è un task separato (factory/seeder/modello), non CMS.
- Se lock events impedisce modifiche CMS: coordinare con agent `w3d` / task `DCP-289`.
