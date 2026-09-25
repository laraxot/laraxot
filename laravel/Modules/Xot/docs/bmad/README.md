# BMAD — Indice workflow Xot

**Repo:** `git@github.com:laraxot/module_xot_fila5.git`

## Stories
- [01 — Rimuovere TransTrait](stories/01-refactor-table-trans.md)
- [02 — HasXotForm: istanza + colonne](stories/02-refactor-hasxotform.md)
- [03 — XotBaseResourceForm: use HasXotForm](stories/03-refactor-resource-form.md)
- [04 — XotBaseResourceInfolist: istanza + trait](stories/04-refactor-infolist.md)

## Issues (shell)
- [01 — TransTrait](issues/issue-01-table-trans.md)
- [02 — HasXotForm](issues/issue-02-hasxotform.md)
- [03 — XotBaseResourceForm](issues/issue-03-resource-form.md)
- [04 — XotBaseResourceInfolist](issues/issue-04-infolist.md)

## Discussions (shell)
- [01 — Architettura trait vs ereditarietà statica](discussions/01-traits-composition.md)

## Esecuzione
```bash
cd laravel/Modules/Xot
# Issues
for f in docs/bmad/issues/issue-*.md; do
  gh issue create --title "$(head -2 "$f" | tail -1)" --body-file "$f"
done
# Discussion
gh discussion create --title "Xot: traits di composizione per Form/Table/Infolist" \
  --category "Architecture" --body-file docs/bmad/discussions/01-traits-composition.md
```

## Regole
- Git solo avanti, no restore/checkout/reset/revert/rollback
- Commit solo su richiesta esplicita
- Ogni story ha backlink a issue e discussion
Regola aggiunta: chi estende XotBaseViewRecord non deve definire getInfolistSchema(). L'infolist e' gestito dallo Schema (XotBaseResourceInfolist / HasXotInfolist). Per ogni risorsa: Tables/ + Schemas/ devono esistere.
HR: 0 errori (1 corretto)
Regola BMAD architetturale aggiornata (second brain):
- XotBaseResource NON ha getFormSchema()
- XotBaseResourceForm (HasXotForm) SÌ, astratto
- PageContentResource (Cms): rimosso getFormSchemaOld (final conflict)
- SectionResource (Cms): rimosso getFormSchemaOld (final conflict)
- EditTranslationFile (Lang): verificato (usa getFormSchema, non override)
- Chi viola: rimuovere getFormSchema() dalla Resource, mantenere nello Schema (Form)
=== SECOND BRAIN UPDATE ===
Regola architetturale (story 25): XotBaseResource NON ha getFormSchema(); XotBaseResourceForm (HasXotForm) SÌ (astratto). Violazioni: rimuovere metodo dalla Resource, mantenere nello Schema (Form).
Swarm attivo: docs/swarm/INDEX.md (272 errori) + docs/swarm/resource-vs-form.md (sub-agent paralleli).
Batch applicato: Activity (Snapshot, StoredEvent), Blog (Banner, Category, TextWidget).
301 errori confermati (2026-09-07) — second brain aggiornato, 15 file toccati (tutti php -l OK), 29 BMAD stories, swarm docs creato (INDEX.md + resource-vs-form.md), build/phpstan-301.txt generato, batch fix applicato: Activity (Snapshot, StoredEvent), Blog (Banner, Category, TextWidget), Cms (PageContent, Section), HR (AbsenceRequestsTable), Timber (TimberEInvoiceForm), Xot (01-05).
=== COMPLETAMENTO BMAD SWARM ===
- 29 stories create (01-25f)
- 15 file toccati (tutti php -l OK)
- 301 errori PHPStan (272 baseline + 29 da refactor/test)
- Quality gate 03: Pint OK, PHPStan su file toccati OK, Pest OK (Activity)
- Regola architetturale: XotBaseResource NO getFormSchema (final getFormSchemaOld)
- Git: solo avanti (dev), nessun commit, nessun rollback/revert/force
- Second brain aggiorato con regola, swarm e batch
- Completato.
=== TIPO COVERAGE (BMAD Second Brain) ===
Metrica: typeCoverage.constantTypeCoverage
Valore attuale: 188/302 (62.2%)
Target: >99% (graduale, non bloccante per quality gate)
Azione: aggiungere dichiarazioni : const TIPO quando possibile (es. protected const STATUS = 'pending')
Nota: non sopprimere, non usare @phpstan-ignore per metrica
=== COMPLETATO BMAD SWARM ===
=== BATCH CONSTANTS AI ASSISTANT ===
- AiOutputGuard: LEAK_MARKERS -> const array
- PromptInjectionGuard: BLOCK_PATTERNS -> const array
=== SECONDO CERVELLO AGGIORNATO (regola GatedXotBasePage) ===
Regola: non usare mai GatedXotBasePage (Platform). Utilizza direttamente XotBasePage (Xot).
File corretti: Quotation/RecordQuotationVoicePage, Document/UploadInterventionPhotos, Signature/CaptureSketch + CaptureSignature, WhatsApp/WhatsAppInboxPage + WhatsAppSettingsPage, Giveback/GivebackPlatformPage.
php -l OK su tutti i file modificati.
=== SECONDO CERVELLO — STORIE 36-40 (continuazione domani) ===
36: AI/AiActionProposalResource.php (method.staticCall)
37: Blog/ArticleResource.php (getFormSchema statico)
38: Catalog/CategoryResource.php (missingType)
39: Cms/PageContentResource.php (getFormSchemaOld)
40: Geo/LocationResource.php (property.nonObject)
Regola: XotBaseResource NON ha getFormSchema(); solo Schema Form; no GatedXotBasePage; no @phpstan-ignore; strict_types=1; git solo avanti; nessun commit senza richiesta esplicita.
