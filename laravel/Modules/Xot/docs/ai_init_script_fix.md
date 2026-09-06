# Aggiornamento Importante: ai_init.sh Script

## Nota di deprecazione

Questo documento riflette il modello legacy "un path reale per ogni tool" ed e'
da considerare superato.

## Modello corretto

Tutti gli adapter di root devono puntare a un solo backend condiviso:

```text
.claude   -> bashscripts/ai/.agents
.cursor   -> bashscripts/ai/.agents
.codex    -> bashscripts/ai/.agents
.gemini   -> bashscripts/ai/.agents
.iflow    -> bashscripts/ai/.agents
.windsurf -> bashscripts/ai/.agents
.zai      -> bashscripts/ai/.agents
```

Tutti dovrebbero mostrare "symbolic link to bashscripts/ai/..."