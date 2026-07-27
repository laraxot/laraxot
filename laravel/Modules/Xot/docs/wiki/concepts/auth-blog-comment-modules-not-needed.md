---
title: "Decisione: moduli Auth, Blog, Comment non necessari in base_workorder_fila5"
type: concept
module: Xot
tags: [modules, auth, blog, comment, project-scope, decision]
created: 2026-07-27
updated: 2026-07-27
related:
  - ./xotbase-migration-religion.md
  - ../../../Tenant/docs/tenant-module-status-registry.md
  - ../../../../../docs/wiki/concepts/git-forward-only-discipline.md
---

# Auth, Blog, Comment — non servono in questo progetto

## Decisione (dell'utente, esplicita)

*"i modulo Comment, Auth e Blog non ci servono in questo progetto"* — base_workorder_fila5
è un gestionale commesse/interventi (WorkOrder, Intervention, TimberBilling,
PublicProcurement, EnergyBroker, …), non un CMS/blog con commenti. I tre moduli sono
probabilmente eredità di un template/boilerplate condiviso con altri progetti
`base_*_fila5` sulla stessa macchina (stesso fenomeno documentato in
[tenant-module-status-registry.md](../../../Tenant/docs/tenant-module-status-registry.md)
per `config/local/workorder/modules_statuses.json`).

## Stato pratico (instabile durante questa sessione, da verificare)

Durante la stessa sessione multi-agente, `Modules/Auth`, `Modules/Blog`, `Modules/Comment`
sono stati rimossi via `git rm` più volte e sono **ricomparsi sul disco come contenuto non
tracciato** subito dopo, senza intervento di questo agente — sintomo di un processo esterno
(sync, backup, altro agente con istruzioni diverse) che li ripristina. Non è stato
identificato con certezza cosa causi il ripristino.

**Prima di ri-based un'azione su questa nota**: verificare lo stato attuale con
`ls laravel/Modules/ | grep -E '^(Auth|Blog|Comment)$'` e `git status --short
laravel/Modules/{Auth,Blog,Comment}/` — non assumere che siano ancora assenti solo perché
questa nota lo dice.

## Cosa fare se si ritrovano presenti

1. **Non ri-rimuoverli ciecamente in loop.** Se sono già stati rimossi e ricompaiono una
   sola volta, va bene ritentare **una volta**; se ricompaiono una seconda volta nella
   stessa sessione, fermarsi e chiedere conferma esplicita all'utente prima di insistere —
   c'è quasi certamente un processo concorrente attivo che li gestisce diversamente.
2. **Registri da tenere allineati alla decisione** (nessuna entry per Auth/Blog/Comment):
   - `laravel/modules_statuses.json` (root, nwidart FileActivator)
   - `laravel/config/modules_statuses.json`
   - `laravel/config/local/{tenant}/modules_statuses.json` (vedi
     [tenant-module-status-registry.md](../../../Tenant/docs/tenant-module-status-registry.md))
3. **Riferimenti incrociati da altri moduli verso Auth/Blog/Comment sono dangling per
   design** — non "ripararli" ricreando i moduli, ma rimuovendo/aggiornando il riferimento
   nel modulo chiamante (esempio reale corretto in questa sessione: 3 test in
   Billing/Intervention/Quotation che importavano
   `Modules\Auth\Database\Seeders\ShieldPermissionsSeeder` sono stati aggiornati per usare
   `Modules\User\Database\Seeders\PermissionsSeeder`, l'equivalente funzionale già presente
   nel modulo che possiede davvero l'autenticazione in questo progetto).

## Follow-up non risolto: tema TwentyOne referenzia ancora Blog

`laravel/Themes/TwentyOne/resources/views/pages/{search,articles/index,articles/[slug],categories/index}.blade.php`
referenziano `Modules\Blog\...` — se il tema `TwentyOne` è effettivamente attivo per
questo progetto, queste pagine falliscono a runtime (classe inesistente) con Blog
rimosso. Non verificato in questa sessione se `TwentyOne` sia il tema realmente in uso
(nome generico da theme boilerplate, sospetto leftover come i moduli stessi) — da
controllare (`config('themes.active')`/equivalente) prima di decidere se: (a) il tema non
è usato e va ignorato, (b) va rimosso anche lui, (c) le 4 pagine vanno riscritte senza
Blog. Non risolto qui per restare nello scope "documentare quanto imparato".

## Perché non semplicemente "restituire" i moduli con contenuto placeholder

Creare stub vuoti (classi/migrazioni fittizie) solo per soddisfare un `use` esistente
altrove viola la stessa filosofia già codificata per PHPStan probe models
(`no-phpstan-probe-models`) e per XotBaseMigration (mai creare tabelle/entità solo per far
"quadrare" un riferimento) — il fix corretto è sempre rimuovere/aggiornare il riferimento
dal lato chiamante, non ricreare l'entità mancante.
