# Risoluzione Problema con ai_init.sh

<<<<<<< HEAD
## Stato attuale

Questa nota descrive un modello ormai superato.

Il canonico attuale e':

```text
.gemini -> bashscripts/ai/.agents
```

Non deve esistere alcuna cartella reale `bashscripts/ai/.gemini`.

## Perche' la versione precedente era sbagliata

L'idea "una cartella dedicata per ogni tool sotto `bashscripts/ai/`" crea shadow
directory, duplicazione e drift tra agent stack diversi. Il progetto ha scelto
una sola fonte di verita':

- config, prompt, skills, memories agenti: `bashscripts/ai/.agents/`
- esposizione verso gli IDE/tool: symlink nella root del repo

## Regola corretta

```text
.claude   -> bashscripts/ai/.agents
.cursor   -> bashscripts/ai/.agents
.codex    -> bashscripts/ai/.agents
.gemini   -> bashscripts/ai/.agents
.iflow    -> bashscripts/ai/.agents
.windsurf -> bashscripts/ai/.agents
.zai      -> bashscripts/ai/.agents
```

## Script canonici

```bash
bash bashscripts/tools/sync-ide-junctions.sh --check
bash bashscripts/tools/sync-ide-junctions.sh
```

`bashscripts/ai/ai_init.sh` va considerato legacy rispetto al modello SSoT
`.agents`.
=======
## Problema Risolto

Lo script `./bashscripts/ai/ai_init.sh` non creava la junction richiesta per la cartella `./bashscripts/ai/.gemini` da vedere dentro `./`.

## Analisi e Soluzione

Dopo l'analisi dello script e verifica del suo comportamento, è stato identificato che:
- Lo script ha la logica corretta per creare il symlink
- Ma per qualche motivo non è stato eseguito correttamente o ha avuto errori

## Azione Correttiva

È stato creato manualmente il symlink richiesto:
```
./.gemini -> ./bashscripts/ai/.gemini
```

## Verifica

Il symlink ora esiste correttamente:
```
lrwxrwxrwx 1 zorin zorin 22 Dec 22 16:17 ./.gemini -> bashscripts/ai/.gemini
```

## Impatto

La cartella `./bashscripts/ai/.gemini` ora è accessibile direttamente dalla root del progetto tramite il symlink `.gemini`, come richiesto.

## Documentazione Aggiornata
>>>>>>> 7f6cf6be (.)

La documentazione del progetto è stata aggiornata per riflettere questo cambiamento.