---
title: "Auto routes"
type: reference
status: active
created: 2026-08-27
updated: 2026-08-27
note: "Convertito da auto_routes.txt (documento) da convert-docs-txt-to-md.py."
---

# Auto routes

/it/tests
va a prendere il modello "home" e vede se esiste la relazione "tests" se esiste usa quelle, altrimenti
va a prendere il "singolar" di tests e va nel solito file xra.php

/it/tests/aaa/zibibbo
va a prendere la relazione "zibibbo" di "aaa" se non la trova "404" differenza da versione vecchia non fa piu' il plural,
implica che nel pannello quando si va a prendere "parents" oltre a row, rows ci deve essere anche "name"
che corrisponde al nome della relazione o della funzione
