# Aggiornamento Documentazione - Problema con ai_init.sh

## Analisi corretta

Il problema non e' "manca la cartella `bashscripts/ai/.gemini`".
Il problema vero e' l'assunzione sbagliata che ogni tool debba avere una propria
directory reale sotto `bashscripts/ai/`.

Questo modello e' stato superato.

## Scopo architetturale

Il progetto vuole:

1. una sola fonte di verita' per regole, skill, prompt e memoria;
2. zero copie shadow per tool diversi;
3. symlink di root come adapter sottili verso il canonico.

La sorgente corretta e':

```text
bashscripts/ai/.agents
```

## Comportamento atteso oggi

```text
.gemini -> bashscripts/ai/.agents
```

Non:

```text
.gemini -> bashscripts/ai/.gemini
```

## Conseguenza pratica

- Source: `/var/www/_bases/base_quaeris_fila4_mono/bashscripts/ai/.gemini`
- Target symlink: `/var/www/_bases/base_quaeris_fila4_mono/.gemini`