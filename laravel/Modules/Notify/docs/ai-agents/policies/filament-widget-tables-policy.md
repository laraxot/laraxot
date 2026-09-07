# Filament Widget Tables Policy

<<<<<<< HEAD
<<<<<<< HEAD
> Indice: [./00-INDEX.md](./00-INDEX.md)
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
> Indice: [./00-INDEX.md](./00-INDEX.md)
>>>>>>> a988596b (first)
> Indice: [./00-index.md](./00-index.md)
> Regola correlata: [../../rules/filament-widget-tables-rule.md](../../rules/filament-widget-tables-rule.md)
> Skill correlata: [../../skills/filament-widget-tables-governance/SKILL.md](../../skills/filament-widget-tables-governance/SKILL.md)

## Principio

Per qualunque elemento che sia una lista, una collezione di outcome o una vista tabellare filtrabile, il canale canonico e un Filament widget table. Blade custom e card grid possono fare da supporto editoriale o hero, ma non sostituiscono search, filter, sort e pagination gia forniti da Filament.

## Quando vale

<<<<<<< HEAD
<<<<<<< HEAD
- outcomes di un mercato predict
- liste di predicts, articles, events, profiles
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
- outcomes di un mercato predict
- liste di predicts, articles, events, profiles
>>>>>>> a988596b (first)
- outcomes di un mercato forecast
- liste di forecasts, articles, events, profiles
- viste operative che richiedono ricerca, ordinamento o filtri
- tabelle front office e back office che devono restare coerenti

## Quando NON vale

- hero summary, badge, trust sections, metriche sintetiche
- blocchi CMS editoriali che non rappresentano dataset navigabili
- micro liste statiche di supporto senza bisogno di search/filter/sort

## Conseguenze architetturali

- creare componenti Blade riusabili per il contorno editoriale
- delegare la lista interattiva al widget Filament
- evitare `@foreach` in Blade quando il problema e realmente tabellare
- preferire una sola fonte di verita per colonne, filtri e ordinamenti

## DRY + KISS

Non duplicare una stessa lista in due implementazioni concorrenti. Se esiste gia un widget table adeguato, si riusa o si estende quello.
