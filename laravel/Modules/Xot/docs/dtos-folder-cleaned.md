---
title: app/DTOs ripulita — FieldDTO/FieldFilterDTO già convertite, note spostate in docs
type: decision
tags: [datas, dto, archive, cleanup]
created: 2026-07-14
---

# app/DTOs ripulita

`Modules/Xot/app/DTOs/` conteneva in precedenza `FieldDTO.php` e
`FieldFilterDTO.php` (non convertite a `Spatie\LaravelData\Data`) — già
rimosse/convertite da un agente concorrente prima che questa sessione
potesse intervenire (vedi `docs/chat/dtos-to-datas-cleanup-2026-07-14.md`).

Rimaneva solo debris non-PHP: `links.md` + `links.txt` (contenuto
identico, note di riferimento su DTO/Actions/generics PHP, non codice).

## Azione

- `links.md` spostato in `Modules/Xot/docs/reference-links-dto-generics-collections.md`
- `links.txt` eliminato (duplicato byte-identico di `links.md`, contenuto
  già preservato — non è stato usato il suffisso `.old` perché non c'era
  perdita di informazione)
- Cartella `app/DTOs/` rimossa (vuota)

## Regola

`app/DTOs/`, `app/DataObjects/`, `app/DataTransferObjects/` (e varianti
minuscole) **non devono esistere** in nessun modulo. Solo `app/Datas/`,
classi che estendono `Spatie\LaravelData\Data`. Vedi
`Modules/UI/docs/datas-not-dtos-convention.md`.
