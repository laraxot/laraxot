---
title: "Sync git dei moduli: 8 repo con storia locale/remota scollegata + 1 remote sparito + repo tema corrotto (recuperato)"
type: story
module: Xot
epic: null
story_id: null
slug: module-git-sync-unrelated-histories
status: in-progress
created: '2026-09-11'
updated: '2026-09-11'
repository: "https://github.com/laraxot/module_xot_fila5.git"
github_issue: null
github_discussion: null
estimated_effort: null
blocked_by: []
blocks: []
owned_scope: []
related:
  - "history-rewrite-force-push-destroys-cited-commits (second brain)"
---

# Sync git dei moduli — stato e piano per continuare

## Contesto

Richiesta utente: verificare che ogni modulo/tema (repo `.git` separato, NO
submodule) sia sincronizzato col proprio remote `laraxot`. Fatto un giro
`git fetch` + confronto commit su tutti i 20 moduli + 1 tema.

## Trovato (2026-09-11 sera)

| Categoria | Repo | Stato |
|---|---|---|
| **In sync** | Xot, Quaeris | Storia condivisa, nessuna azione |
| **Divergenza reale minima** (storia condivisa, 1 commit per lato) | AI, Chart, CloudStorage, DbForge, Gdpr, Limesurvey, Tenant | Serve `fetch` + `merge --no-edit` (MAI `git pull`, `pull.rebase=true` e' settato — vedi memoria `git-pull-rebase-true-usa-merge-esplicito`), poi verificare con phpstan/pest |
| **Solo dietro** | User | `git merge laraxot/dev` (fast-forward), nessun conflitto atteso |
| **Storia SCOLLEGATA** (nessun antenato comune, remote ha 1 solo commit "iniziale") | Activity (locale 2839 commit!), Cms, Geo, Job, Lang, Media, Notify (locale 19), UI | Serve `git merge --allow-unrelated-histories laraxot/dev` modulo per modulo, risolvere i conflitti (probabilmente quasi ogni file, essendo storie indipendenti), **MAI force-push** (istruzione esplicita utente) |
| **Remote non trovato** | Setting | `git@github.com:laraxot/base_setting_fila5.git` → "Repository not found" — verificare se rinominato/eliminato/permessi, non presumere |
| **Repo locale corrotto, RECUPERATO** | Zero (tema) | `git fsck` trovava un commit mancante (`55ab1d13...`); recuperato con `git fetch laraxot <sha-mancante>` (l'oggetto esisteva sul remote); poi push pulito fast-forward (6 commit). ✅ Chiuso. |

## Perche' non ho gia' fatto i merge

L'utente ha risposto "mai force nel push! casomai fai fetch e merge e sistema
le collisioni, sempre controllando con gli strumenti soliti" — quindi il piano
e' fetch+merge (anche con `--allow-unrelated-histories` dove serve), MAI
force-push, MAI reset distruttivo. Non ancora eseguito per gli 8 moduli a
storia scollegata: sono conflitti potenzialmente su interi alberi di file
(storie indipendenti), da fare con calma modulo per modulo, verificando con
phpstan+pest DOPO ogni merge (pilastro 5 standing order) prima di passare al
successivo — lavoro sostanzioso, non concluso in questa sessione.

**Nota di rischio aggiuntiva**: durante questa stessa sessione un'ALTRA
sessione concorrente ha fatto un vero e proprio force-push/history-rewrite sul
modulo Xot (vedi `history-rewrite-force-push-destroys-cited-commits` nel
second brain, e commit `9d04558c`) — quindi il rischio di collisione fra
sessioni concorrenti su questi merge e' concreto, non teorico. Fare un merge
alla volta, verificare subito, committare/pushare subito per minimizzare la
finestra di collisione.

## Piano per domani

1. Per i 6 moduli a divergenza reale minima + User: `git fetch laraxot dev &&
   git merge laraxot/dev --no-edit`, risolvere eventuali conflitti (probabilmente
   nessuno, sono diversi di un solo commit), verificare phpstan+pest, commit+push.
2. Per gli 8 moduli a storia scollegata: uno alla volta,
   `git merge --allow-unrelated-histories laraxot/dev --no-edit`, aspettarsi
   conflitti estesi, risolvere favorendo la versione locale per il codice (la
   storia locale e' presumibilmente quella "vera" visto il volume di commit,
   es. Activity 2839 vs 1) mostrando prima il diff all'utente per i file
   grossi/ambigui. Verificare phpstan+pest dopo ognuno.
3. Setting: chiedere all'utente se il repo remote e' stato rinominato/spostato
   prima di agire.
4. Aggiornare questa story a "done" quando tutti i 20+1 repo sono confermati
   in sync (0 ahead/0 behind o merge pulito).

## Acceptance criteria

- [ ] AI, Chart, CloudStorage, DbForge, Gdpr, Limesurvey, Tenant, User: merge fatto, push fatto, phpstan/pest verdi
- [ ] Activity, Cms, Geo, Job, Lang, Media, Notify, UI: merge con `--allow-unrelated-histories` fatto, conflitti risolti, push fatto, phpstan/pest verdi
- [x] Zero: recuperato e sincronizzato
- [ ] Setting: causa del remote mancante chiarita con l'utente
