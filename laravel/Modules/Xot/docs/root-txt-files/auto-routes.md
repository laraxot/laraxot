---
title: 'Auto routes'
module: Xot
type: reference
slug: auto-routes
description: '/it/tests va a prendere il modello "home" e vede se esiste la relazione "tests" se esiste usa quelle, altrimenti va a prendere il "singolar" di tests e va nel solito file xra.php'
tags: [migrato-da-txt, xot]
converted_from: auto_routes.txt
created: 2026-08-24
updated: 2026-08-24
---

/it/tests
va a prendere il modello "home" e vede se esiste la relazione "tests" se esiste usa quelle, altrimenti
va a prendere il "singolar" di tests e va nel solito file xra.php

/it/tests/aaa/zibibbo
va a prendere la relazione "zibibbo" di "aaa" se non la trova "404" differenza da versione vecchia non fa piu' il plural,
implica che nel pannello quando si va a prendere "parents" oltre a row, rows ci deve essere anche "name"
che corrisponde al nome della relazione o della funzione
