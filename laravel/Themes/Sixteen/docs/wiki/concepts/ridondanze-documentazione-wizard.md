---
title: "Documentazione wizard Sixteen ridondanza intenzionale vs duplicabile"
type: concept
theme: sixteen
created: "2026-05-21"
updated: "2026-05-21"
related:
  - ../../../../docs/ridondanze-documentazione-temi.md
  - ../../wizard-refactor-explanation.md
  - ../../ticket-wizard-filament-refactor.md
  - ../../architecture/wizard-parity.md
  - ../../design-comuni/TICKET-CREATION-WIZARD.md
  - ../../design-comuni/wizard-governance-bridge.md
  - ../../../../../Modules/Xot/docs/wiki/concepts/ridondanze-cross-cutting-codebase.md
  - ./fixcity-wizard-full-width-and-opaque-fields.md
---

# Ridondanza / sovrapposizioni documentazione wizard (tema Sixteen)

## Contesto di business logic

Nel tema **Sixteen** il wizard “ticket / segnalazione” coincide con parity **Design Comuni** + personalizzazioni Filament pubblicate nei moduli. La documentazione tende quindi ad accumulare **slice verticali** (una pagina per una singola regressione/regola parity) anche se il tema concettuale torna ricorrente («wizard pubblico», «parity passo 2», «CSS tema»).

Ripetizioni **storiche refactor** modulo vs tema dove la logica vive ora in **`Modules\...`** sono **candidate DRY editoriali**.

## Coppie quasi uguali sul refactor Filament wizard

Percorsi sotto **`docs/`** che raccontano lo stesso arco refactor e **andranno fusi editorialmente**:

- [`wizard-refactor-explanation.md`](../../wizard-refactor-explanation.md) (livello alto)
- [`ticket-wizard-filament-refactor.md`](../../ticket-wizard-filament-refactor.md) (implementazione tecnica)

**Politica suggerita:** tenere **`wizard-refactor-explanation.md`** come narrativa; la seconda può degradare in sezione tecnica dentro la prima o trasformarsi in `…-technical-appendix.md`.

## Fascia parity / Governance Design Comuni

Documenti vicini nell’intent (parity “crea ticket”):

- [`design-comuni/TICKET-CREATION-WIZARD.md`](../../design-comuni/TICKET-CREATION-WIZARD.md) — sintesi progettuale Governance
- [`design-comuni/wizard-governance-bridge.md`](../../design-comuni/wizard-governance-bridge.md) — ponte modulo/tema/reference esterna

Non sono byte-identici: **mantenere** ma **aggiungere un paragrafo “vedi anche” crociato** in ciascuno (evita terza pagina con stesso contenuto).

## Fascia tecnica tema (CSS/visual)

Vari file `wiki/concepts/*wizard*` più [`best-practices/filament-wizard-patterns.md`](../../best-practices/filament-wizard-patterns.md) e macro-concetti DaisyUI/stack (se presenti). Qui la ridondanza è spesso **voluta slicing** (“una regola = un grep-friendly title”). Prima di fondere pagine confrontare **`tags:`** nei frontmatter wiki e decidere merge solo se gli heading coprono gli stessi failure mode.

Logica nucleo Filament (trait `HasWizard`, `XotBaseWizardWidget`): **canonical** modulo **Xot** — tema linka solo; vedi **[ridondanze-cross-cutting-codebase.md](../../../../../Modules/Xot/docs/wiki/concepts/ridondanze-cross-cutting-codebase.md)**.

## Lista rapida wizard-related (priorità aggiornamento)

| Area | Percorsi (da non duplicare senza ragione) |
|------|-------------------------------------------|
| Parità visiva / step | `architecture/wizard-parity.md`, `architecture/wizard-step-visibility.md`, `wiki/concepts/wizard-visual-parity.md` |
| Custom view / filosofia zen | `wiki/concepts/wizard-custom-view-architecture.md`, `zen-pubthemewizard-philosophy.md`, `wizard-component.md`, `themes/*css*parity*wizard*` |
| Review / miglioramenti | `wiki/design/wizard-review-parity.md`, `wizard/form-tag-fix.md` |
