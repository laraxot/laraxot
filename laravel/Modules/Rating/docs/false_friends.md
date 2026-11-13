---
title: "false friends"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-09-26
qmd: "false friends"
issues: []
discussions: []
status: superseded
superseded_by: false-friends.md
---

# False Friends – Rating

| Falso Amico | Perché è fuorviante | Soluzione |
|-------------|---------------------|-----------|
| `rating_count` = `reviews_count` | Conta anche rating senza testo | Usa `review_count()` distinto |
| `avg(rating)` = `popularity` | Ignora il numero di voti | Normalizza per numero voti |
| 4.5 ⭐ è "ottimo" | Dipende da scala e contesto | Definisci soglie chiare |
| Rating sempre crescente | Non considera il tempo | Usa weighted average |