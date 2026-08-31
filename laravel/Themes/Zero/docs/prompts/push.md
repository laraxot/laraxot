---
title: 'Push'
module: Zero
type: reference
slug: push
description: 'Obiettivo: sincronizzare il tema Zero su tutti i remote GitHub configurati.'
tags: [migrato-da-txt, zero]
converted_from: push.txt
created: 2026-08-24
updated: 2026-08-24
---

Obiettivo: sincronizzare il tema Zero su tutti i remote GitHub configurati.

Procedura richiesta:
1. Vai in laravel/Themes/Zero.
2. Esegui git remote -v e usa solo gli org emersi dai remote reali.
3. Esegui fetch di tutti i remote.
4. Confronta HEAD con <remote>/dev:
   - se HEAD e' avanti e il remote non ha commit mancanti, fai push fast-forward;
   - se un remote ha commit non presenti localmente, fermati e documenta la divergenza;
   - non usare force push, reset, restore, checkout, switch o revert.
5. Dopo ogni push, ricontrolla che HEAD e tutti i remote dev puntino allo stesso stato.
6. Documenta in docs/ come e' stata risolta la sincronizzazione.
7. Aggiorna il second brain locale se emergono regole operative riusabili.

Output finale: remoti sincronizzati, verifiche eseguite, file documentali modificati.
