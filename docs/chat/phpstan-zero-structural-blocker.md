---
title: "PHPStan zero-errori: blocco strutturale non risolvibile senza toccare phpstan.neon"
created: 2026-07-27
---

# Blocco strutturale per "zero errori" — leggere prima di continuare la caccia

## Il problema

`laravel/phpstan.neon` (root, sacro, nessun agente può modificarlo) contiene:

```neon
ignoreErrors:
    - '#PHPDoc tag @mixin contains unknown class #'
```

**Verificato live, scan completo `Modules/` (274 errori totali, 2026-07-27 14:5x):**

```
Ignored error pattern #PHPDoc tag @mixin contains unknown class # was not
matched in reported errors.
[ERROR] Found 1 error
```

Questo pattern non matcha **nessun** errore reale in **nessun** modulo, su scan
dell'intero `Modules/`. PHPStan tratta un ignore-pattern inutilizzato come un
errore a sé (comportamento standard, serve a tenere pulita la lista ignore nel
tempo). Il pattern esisteva probabilmente per sopprimere un `@mixin` che è
stato rimosso/corretto altrove — la config non è stata aggiornata di
conseguenza.

## Perché è un vero blocco

- Non è un bug in nessun modulo — è un residuo nella config root.
- Nessun fix a livello di `Modules/*` può farlo sparire: non c'è nessun
  `@mixin contains unknown class` reale da "guarire" da nessuna parte.
- **"Zero errori" con questo constraint attivo è strutturalmente irraggiungibile**
  finché quella riga non viene rimossa da `phpstan.neon` — ma la regola
  esplicita di questo task è "non puoi modificare phpstan.neon".

## Cosa NON fare

- Non creare `@phpstan-ignore` locali per "nascondere" questo (non è un errore
  di file, è un errore sulla config stessa — non si sopprime a livello file).
- Non aggiungere un `@mixin` fittizio da qualche parte solo per far matchare
  il pattern e farlo sparire — sarebbe inventare codice inutile per ingannare
  lo strumento, l'opposto dello spirito della regola "fix the underlying
  cause, do not just make the error go away".
- Non modificare `phpstan.neon` senza autorizzazione esplicita dell'utente.

## Cosa fare

Questo va segnalato all'utente/owner del progetto come decisione loro da
prendere: o rimuovono questa riga da `phpstan.neon` (unica vera fix), o
accettano "1 errore residuo, non risolvibile per vincolo esplicito" come
baseline realistico per "zero" in questo progetto.

## Coordinamento

Se stai lavorando su un modulo e il tuo scan mostra SOLO questo 1 errore
(nessun altro): il tuo modulo è a **zero errori reali**. Non perdere tempo a
cercare cosa "manca" — non c'è niente da correggere lì, è questo blocco
globale.

GitHub issue di riferimento: laraxot/base_workorder_fila5#14
