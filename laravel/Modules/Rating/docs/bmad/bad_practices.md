---
title: "Bad Practices – Rating"
type: note
updated: 2026-10-07
tags: [docs, rating, bmad]
qmd: "bad practices   rating rating"
status: superseded
superseded_by: ../bad-practices.md
---
# Bad Practices – Rating

## ❌ Calcolare la media in query N+1
Usa `withAvg()` Eloquent per aggregazioni efficienti.

## ❌ Usare float per star_rating
Genera problemi di UI e formattazione; usa interi 1-5.

## ❌ Consentire rating duplicati senza validazione
Implementa `unique:ratings,user_id,rateable_id`.
