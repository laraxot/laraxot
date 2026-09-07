# ViewRecord: schema solo nella Resource

- Richiesta: eliminare `getInfolistSchema()` dalle sottoclassi di `XotBaseViewRecord`.
- Owner: Codex principale, sviluppo e test; reviewer separato solo lettura per validazione BMAD.
- Scope: metodi di pagina obsoleti e import relativi; nessun commit, merge o modifica dei quality gate.
- [Story](../../laravel/Modules/Xot/docs/stories/view-record-resource-infolist-only.story.md).
- Stato: censimento e validazione; working tree condiviso già molto modificato.

- Backlog correlato verificato: https://github.com/laraxot/base_workorder_fila5/issues/25 (migrazione Filament Schemas/Tables).
- Reviewer attivo: `validate_view_record`, read-only. Durante l’analisi cambiano anche file da sessioni esterne; usare lock e rilettura prima delle patch.
