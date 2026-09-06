# Aggiornamento Documentazione - Problema con ai_init.sh

<<<<<<< HEAD
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
=======
## Problema Identificato

Lo script `./bashscripts/ai/ai_init.sh` non crea la junction richiesta per la cartella `./bashscripts/ai/.gemini` da vedere dentro `./`.

## Analisi

Dopo l'analisi dello script, è stato identificato un problema logico nell'implementazione:

- Lo script cerca cartelle nella root del progetto (come `.gemini`)
- Poi crea symlink da `bashscripts/ai/.$nome` a quelle cartelle
- Ma invece dovrebbe cercare cartelle specifiche in `bashscripts/ai/` (come `.gemini`) e creare symlink nella root del progetto che puntano a quelle cartelle

## Comportamento Atteso

Dovrebbe creare un symlink nella root del progetto:
```
./.gemini -> ./bashscripts/ai/.gemini
```

## Comportamento Attuale

Lo script cerca una cartella `.gemini` nella root del progetto e crea un symlink in `bashscripts/ai/` che punta a quella cartella (se esistesse).

## Soluzione

Lo script deve essere corretto per invertire la logica:
- Cercare le cartelle specifiche in `bashscripts/ai/` 
- Creare symlink nella root del progetto che puntano a quelle cartelle

## Cartelle Coinvolte

- Source: `./bashscripts/ai/.gemini`
- Target symlink: `./.gemini`
>>>>>>> 7f6cf6be (.)
