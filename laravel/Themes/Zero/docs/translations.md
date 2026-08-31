---
title: "Traduzioni del tema Zero"
type: reference
tags: [translations, i18n, theme]
created: 2026-08-06
updated: 2026-08-06
qmd: "zero theme translations traduzioni tema"
---

# Traduzioni del tema Zero

## Cosa vive qui

`Themes/Zero/lang/{locale}/` contiene solo le stringhe del front-office che appartengono al
tema: voci del menu pubblico, etichette di layout, testi di interfaccia non legati a un
modulo. Lingue presenti: `it`, `en`, `de`.

| File | Contenuto |
|---|---|
| `navigation.php` | Voci del menu pubblico (home, chi siamo, contatti, servizi, ...) |
| `ui.php` | Etichette di interfaccia condivise dai componenti del tema |

## Confine con le traduzioni dei moduli

`Themes/*/lang/*/navigation.php` riguarda la **navigazione del sito pubblico**. Non ha
nulla a che vedere con `navigation.group` dei file di traduzione dei moduli, che governa i
gruppi del menu Filament (vedi
[gruppi di navigazione del modulo User](../../../Modules/User/docs/navigation-groups.md)).
Sono due sistemi distinti che condividono solo la parola "navigation".

Regola: una stringa che descrive un'entita' di dominio (utente, team, grafico) sta nel file
di traduzione del modulo. Una stringa che esiste solo perche' il tema la disegna sta qui.

## Placeholder da riempire per progetto

`navigation.site_title` vale `<nome progetto>` in tutte e tre le lingue. E' un segnaposto
voluto: il tema e' condiviso tra piu' progetti e non puo' contenere il nome di uno di essi
(vedi la regola di neutralita' della documentazione dei moduli). Il valore va sovrascritto
a livello di progetto, non modificato qui.

## Convenzioni

1. Chiavi in `snake_case`, file in `lowercase`.
2. Stessa struttura di chiavi in tutte le lingue: se aggiungi una chiave in `it`, la
   aggiungi anche in `en` e `de` nello stesso commit. Una chiave presente in una sola
   lingua produce la chiave grezza a schermo per le altre.
3. Nessuna stringa hardcoded nelle Blade del tema: sempre `__('zero::navigation.home')`.
4. Niente valori uguali alla chiave (`'home' => 'home'`): e' il sintomo di traduzione mai
   scritta, non un valore valido.

## Verifica

```bash
# parita' di chiavi fra le lingue del tema
for f in navigation ui; do
  for l in it en de; do
    echo "== $l/$f"; php -r "print_r(array_keys(require 'Themes/Zero/lang/$l/$f.php'));"
  done
done
```

## Correlati

- [00-index.md](./00-index.md)
- [customization.md](./customization.md)
- Regola condivisa: `docs/wiki/rules/translation-standards.md`
