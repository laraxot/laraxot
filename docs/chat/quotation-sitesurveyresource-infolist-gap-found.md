---
title: "Quotation SiteSurveyResource: Resource-level infolist silently empty (found, not fixed)"
type: coordination
tags: [filament, quotation, infolist, canonical-structure, epic-4]
created: 2026-09-07T00:00:00Z
agent: claude (sonnet 5) — this session
---

# SiteSurveyResource — gap trovato, non ancora corretto

Per chi esegue lo swarm `filament-canonical-structure-14-modules` (Epic 4) su
Quotation: `Modules/Quotation/app/Filament/Resources/SiteSurveyResource` ha
gia' `Schemas/SiteSurveyForm.php` e `Tables/SiteSurveysTable.php` (form/table
OK), ma **manca `Schemas/SiteSurveyInfolist.php`**, e il Resource stesso non
sovrascrive `getInfolistSchema()`.

`XotBaseResource::infolist()` (final) cerca `Schemas\SiteSurveyInfolist`, non
trovandola ricade su `static::getInfolistSchema()` del Resource — che nella
classe base ritorna `[]`. Il motivo per cui questo non e' mai stato notato:
`Pages/ViewSiteSurvey.php` (extends `XotBaseViewRecord`) definisce il proprio
`getInfolistSchema()` inline — pattern corretto e sanzionato per le View
page (`XotBaseViewRecord::infolist()` e' final e chiama sempre il metodo
abstract della Page, nessun auto-discovery `Schemas/` a livello Page). Quindi
la pagina View funziona benissimo; e' il Resource-level infolist ad essere
vuoto in silenzio — impatta solo path alternativi (es. un `ViewAction` su
tabella aperto come modal/slide-over invece che navigazione a pagina piena,
o un infolist embedded altrove che referenzia il Resource direttamente).

**Fix suggerito**: creare `Schemas/SiteSurveyInfolist.php extends
XotBaseResourceInfolist` con lo stesso contenuto gia' presente in
`ViewSiteSurvey::getInfolistSchema()` (7 campi: code, title,
customer.legal_name, status_code, scheduled_at, performed_at, media_count),
oppure verificare se nessun path alternativo lo consulta mai e documentare
la scelta esplicita di lasciarlo vuoto (non silenziosa).

**Nota per lo script di audit**: questa distinzione (infolist Resource-level
vs Page-level) non risulta rilevata da `bashscripts/tools/filament_audit.sh`
allo stato attuale — vale la pena estendere il check. Vedi second-brain
`pattern_resource_vs_page_infolist_two_paths.md`.

Non toccato nessun file di codice in questa sessione — solo lettura +
documentazione, per non collidere con lo swarm Epic 4 gia' attivo su
Quotation.
