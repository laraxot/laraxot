# Aggiornamento Documentazione - Problema con ai_init.sh

## Problema Identificato

<<<<<<< HEAD
Lo script `./bashscripts/ai/ai_init.sh` non crea la junction richiesta per la cartella `./bashscripts/ai/.gemini` da vedere dentro `./`.
=======
Lo script `bashscripts/ai/ai_init.sh` non crea la junction richiesta per la cartella `bashscripts/ai/.gemini` da vedere dentro ``.
>>>>>>> 7f6cf6be (.)

## Analisi

Dopo l'analisi dello script, è stato identificato un problema logico nell'implementazione:

- Lo script cerca cartelle nella root del progetto (come `.gemini`)
- Poi crea symlink da `bashscripts/ai/.$nome` a quelle cartelle
- Ma invece dovrebbe cercare cartelle specifiche in `bashscripts/ai/` (come `.gemini`) e creare symlink nella root del progetto che puntano a quelle cartelle

## Comportamento Atteso

Dovrebbe creare un symlink nella root del progetto:
```
<<<<<<< HEAD
./.gemini -> ./bashscripts/ai/.gemini
=======
.gemini -> bashscripts/ai/.gemini
>>>>>>> 7f6cf6be (.)
```

## Comportamento Attuale

Lo script cerca una cartella `.gemini` nella root del progetto e crea un symlink in `bashscripts/ai/` che punta a quella cartella (se esistesse).

## Soluzione

Lo script deve essere corretto per invertire la logica:
<<<<<<< HEAD
- Cercare le cartelle specifiche in `bashscripts/ai/` 
=======
- Cercare le cartelle specifiche in `bashscripts/ai/`
>>>>>>> 7f6cf6be (.)
- Creare symlink nella root del progetto che puntano a quelle cartelle

## Cartelle Coinvolte

<<<<<<< HEAD
- Source: `./bashscripts/ai/.gemini`
- Target symlink: `./.gemini`
=======
- Source: `bashscripts/ai/.gemini`
- Target symlink: `.gemini`
>>>>>>> 7f6cf6be (.)
