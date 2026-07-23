---
title: "Blog — User e Comment"
type: concept
module: Blog
tags: [blog, user, comment, boundary]
created: 2026-06-29
updated: 2026-06-29
qmd: "Blog module User Comment Article comments consumer CanComment"
issues:
  - "https://github.com/laraxot/base_predict_fila5/issues/216"
discussions:
  - "https://github.com/laraxot/base_predict_fila5/discussions/217"
related:
  - ../../../../../../docs/wiki/concepts/module-user-comment-dependency-direction.md
  - ../../../User/docs/wiki/concepts/no-comment-module-dependency.md
  - ../../../Comment/docs/wiki/concepts/depends-on-user-module.md
---

# Blog — User e Comment

- **User** non dipende da Comment (infrastruttura pulita).
- **Comment** può dipendere da User.
- Articoli/commenti FO: il modulo **consumer** (es. Predict su `Article`) collega Comment via trait/contratti nel dominio, non spostando coupling in User core.
