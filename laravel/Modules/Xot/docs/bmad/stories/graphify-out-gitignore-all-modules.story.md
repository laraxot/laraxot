# Story: graphify-out/ ignorato in tutti i .gitignore dei moduli

## Contesto
Richiesta utente 2026-09-22: `graphify-out/` (output generato da `graphify update .`,
knowledge graph rigenerabile, vedi CLAUDE.md root "graphify") va in `.gitignore` in
TUTTI i moduli, non solo alcuni. Ogni modulo ha `.git` proprio (no submodule).

## Perche'
`graphify-out/` e' artefatto locale rigenerabile (come `vendor/`/`node_modules/`),
contiene anche dump di query passate (`graphify-out/memory/*.md`) — non va versionato,
gonfia il repo, diverge per sessione/agente.

## Audit (18 moduli)
- gia' con riga `graphify-out/` in .gitignore: Activity, IndennitaResponsabilita, Job,
  Notify, Ptv, Rating, Tenant, UI, User, Xot (10)
- SENZA riga (da aggiungere): Incentivi, IndennitaCondizioniLavoro, Lang, Media, Pdnd,
  Performance, Progressioni, Sigma (8)
- con file gia' TRACKED da rimuovere dall'indice (git rm -r --cached graphify-out/):
  Activity(7), Job(8), Lang(17), Media(4), Performance(7), Progressioni(7), Xot(4) (7 moduli)

## Azione per modulo
1. Aggiungere `graphify-out/` a `.gitignore` se assente.
2. Se tracked: `git rm -r --cached graphify-out/` (mantiene i file su disco).
3. Commit nel repo del modulo, pull+push su tutti i remote.

## Gate
Nessun codice PHP toccato (solo .gitignore + untrack) -> phpstan/phpmd/phpinsights/pest
NON pertinenti, skip giustificato (nessun file .php modificato).

## Esecuzione
Swarm 6 subagent paralleli, 3 moduli ciascuno, ordine random, lock per-modulo su
bashscripts/lock/ prima dell'edit.

## Stato
in-progress -> vedi docs/sprint-status.yaml chiave Xot/graphify-out-gitignore-all-modules
