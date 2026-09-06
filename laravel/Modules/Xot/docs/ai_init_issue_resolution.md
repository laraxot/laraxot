# Risoluzione Problema con ai_init.sh

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

La documentazione del progetto è stata aggiornata per riflettere questo cambiamento.