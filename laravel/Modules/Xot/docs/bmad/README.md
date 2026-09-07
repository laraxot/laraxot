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
