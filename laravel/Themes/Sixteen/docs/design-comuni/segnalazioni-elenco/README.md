---
title: "README"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-09-26
qmd: "README"
issues: []
discussions: []
status: draft
---

# Segnalazioni elenco

Data: 2026-04-04

## Obiettivo
Allineare il layout della pagina `tests.ticket-list` alla reference, mantenendo filtro + mappa affiancati anche quando si passa alla tab "Elenco" e documentando i pulsanti di filtro e `Elenco`/`Mappa`.

## Cosa ho fatto
- creato il nuovo blocco `pub_theme::components.blocks.segnalazioni.layout` che contiene sidebar + tab mappa/elenco con `x-data` per la logica delle tab/modali;
- trasferito i dati delle categorie direttamente nel blocco (via proprietà `filters.items`), eliminando il blocco `sidebar-filters` duplicato;
- aggiornato il JSON `laravel/config/local/fixcity/database/content/pages/tests.ticket-list.json` sul tema per usare il blocco nuovo e passare `filters`, `tabs`, `cta`, `items`, `results_count`;
- rigenerati gli asset (`npm run build`, `npm run copy`) e pulite le cache (`php artisan optimize:clear`).

## Verifica
- l'HTML locale mostra ora il `row segnalazioni-layout` con la colonna filtri (`col-lg-3`) e la colonna principale (`col-lg-9`) contenente mappa/tab ed elenco;
- cliccando su "Elenco" la mappa resta visibile a lato mentre le schede dettagli si dispongono sul lato destro; i pulsanti "Filtra" (mobile) e "Rimuovi filtri" mantengono lo stesso significato originale della reference; i filtri scompaiono su mobile via modal `showFilterModal`.
- gli screenshot aggiornati sono in `local.png` (locale) e `reference.png` (reference).

## Artifacts
- [local.png](./local.png)
- [reference.png](./reference.png)

## Next passi
- stabilire eventuali variazioni visive nel tab "Mappa" (modal immagini + pin). Se ulteriori differenze emergono, aggiornare i CSS/JS specifici all'interno di `resource/css/app.css` e, se serve, aggiungere un piccolo script Alpine alla pagina stessa.

## Documentazione

| Lingua | Link |
|--------|------|
| 🇮🇹 Presentazione | Questo file (`README.md`) |
| 🇬🇧 Business card | [docs/readme-en.md](../../readme-en.md) |
| 📚 Wiki tecnica | [./docs/wiki/](../..) |

---
**Modulo** `Sixteen` · **Laraxot** · **FixCity Platform** · PHPStan 10 · Filament 5
