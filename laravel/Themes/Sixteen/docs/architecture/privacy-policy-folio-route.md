---
title: "Public privacy policy route ownership"
type: architecture-note
status: active
created: 2026-09-27
updated: 2026-09-27
tags: [theme, fixcity, folio, privacy, route-precedence]
issues:
  - "https://github.com/laraxot/base_fixcity_fila5/issues/383"
discussions:
  - "https://github.com/laraxot/base_fixcity_fila5/discussions/392"
related:
  - ../../../../Modules/Fixcity/docs/bmad/stories/STORY-FIXCITY-PRIVACY-PUBLICATION-GATE.md
qmd: "route and ownership sixteen"
---

# Route and ownership

`XotData::getPubThemeViewPath()` resolves the configured `Sixteen` theme to
`Themes/Sixteen/resources/views/pages`. Folio matches a literal directory index
before wildcard directory indexes; the explicit
`resources/views/pages/privacy/index.blade.php` therefore owns `/it|en/privacy`
ahead of the CMS `[container0]/index` catch-all.

The theme page owns route precedence and hands off rendering to the FixCity
module. The module action resolves the tenant-localized `policy.md`; its view
renders configured content and fails closed with HTTP 503 while the file is the
starter placeholder. Legal copy, data-controller identity and contacts remain
tenant-owned values. Do not add demo legal data to this theme.

## Correzione 2026-10-13 (DCP-130): questa nota descrive uno stato che non esiste piu'

Il commit `30d521c` (2026-10-11) ha cancellato `resources/views/pages/privacy/index.blade.php`: da allora `/it/privacy` rispondeva 404
(provato con `curl`), nonostante lo `status: active` qui sopra. La nota resta come cronologia; lo stato vero e' questo:

- `/{lingua}/privacy` e' la pagina CMS `privacy.index` (JSON `layout: design-comuni`), servita dal catch-all `pages/[container0]/index`
  con il blocco `components/blocks/legal-document/design-comuni` (nessuna pagina Folio dedicata).
- Il testo e' ancora il `policy.md` del tenant, letto da `GetPublishedLegalDocumentAction` e reso da `BuildLegalDocumentAction`.
- Senza testo, o con testo incompleto (sezioni dell'art. 13 GDPR mancanti, segnaposto), la pagina NON risponde piu' 503: risponde 200
  con `<h1>` e un avviso che elenca che cosa deve ancora fornire il titolare (`AssessPrivacyPolicyAction`). Il blocco di go-live e' passato
  al test `PrivacyPolicyPublicationGateTest` (rosso finche' il Comune non fornisce il testo).
- Modello da compilare: `Modules/Fixcity/resources/templates/privacy-policy.<lingua>.md`.

Story: `Modules/Fixcity/docs/stories/dcp-130-informativa-privacy-art13-e-pagina-servita.story.md`.

