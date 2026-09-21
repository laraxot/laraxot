---
title: "settings.json invalido (disabilitava tutti gli hook) + nuovo hook GitHub-reminder"
type: story
module: Xot
epic: null
story_id: null
slug: settings-json-repair-and-github-reminder-hook
created: '2026-09-11'
updated: '2026-09-11'
status: done
repository: "https://github.com/laraxot/module_xot_fila5.git"
github_issue: "https://github.com/laraxot/module_xot_fila5/issues/115"
github_discussion: "https://github.com/laraxot/module_xot_fila5/discussions/117"
---

# settings.json invalido + hook GitHub-reminder

## Story

Richiesta esplicita dell'utente, ripetuta 3 volte nella sessione del
2026-09-11: "ogni modifica che fai nel codice devi spiegarla e discuterla
nelle github issue e github discussion sempre, fa tutto quello che puoi
per non dimenticare mai questa regola". Una memoria da sola non basta per
un "sempre" legato a un evento (vedi
[[php-edit-verify-hook-not-just-memory]]) — serve un hook reale.

## Cosa e' stato fatto

1. **Trovato mentre si costruiva l'hook, non cercato apposta**:
   `.claude/settings.json` (symlink a `bashscripts/ai/.agents/settings.json`
   — stesso file, verificato con `readlink -f`) era JSON **invalido**
   (`jq empty` falliva): un blocco `"permissions": { "allow": [` mancante
   lasciava ~64 voci di array senza chiave, e un blocco `PostToolUse` aveva
   due `"matcher"` nello stesso oggetto senza virgola separatrice. Un
   settings.json rotto disabilita **silenziosamente tutti** gli hook del
   progetto — incluso `php-verify-reminder.sh` (gate phpstan/pest gia'
   esistente), che non stava scattando da tempo indeterminato senza errori
   visibili.
2. Ricostruiti entrambi i file (stesso contenuto, due path) unendo le due
   liste `permissions.allow`/`deny` sparse (826 voci allow, 22 deny dopo
   dedup — nessuna voce rimossa). Validato con `jq empty`.
3. Creato `bashscripts/ai/hooks/github-explain-reminder.sh`, agganciato
   come terzo hook `PostToolUse` `Edit|Write|MultiEdit`: ad ogni scrittura
   di un `.php` sotto `laravel/Modules/<Modulo>/`, cerca fra le story del
   modulo quella con `owned_scope` che cita il file E un
   `github_issue`/`github_discussion` reale (non `null`), inietta il
   promemoria con i link gia' pronti. Provato dal vivo (non solo
   pipe-test): un `Write` di prova ha prodotto davvero il messaggio nel
   contesto, watcher gia' attivo senza bisogno di riavvio.

## Verifica

- `jq empty` su entrambi i file: OK.
- Hook verificato in produzione per tutto il resto della sessione: ha
  effettivamente ricordato di aggiornare GitHub ad ogni Edit su file con
  story tracciata (visibile nei turni successivi di questa stessa
  sessione).

## Per continuare domani

Nessun'azione pendente su questa story specifica — chiusa. Se in futuro
`.claude/settings.json` torna a comportarsi in modo strano (hook che non
scattano), il primo controllo e' `jq empty .claude/settings.json` prima di
qualunque altra ipotesi (vedi second brain
[[github-explain-reminder-hook-and-settings-json-corruption]]).

Nota di processo: `bashscripts/` e' un repo git separato
(`bashscripts_fila5`), ignorato dal `.gitignore` della root — le modifiche
qui sono state committate/pushate li', non nella root.
