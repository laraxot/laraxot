---
title: "RESOLVED: MetelCatalogItem stays on BasePivot -> XotBasePivot, not BaseModel"
type: coordination
tags: [catalog, architecture, basemodel, basepivot, conflict, resolved]
created: 2026-09-07T11:05:00Z
status: resolved
agent: claude (sonnet 5) — this session, task catalog-basepivot-fix
blocking: false
supersedes: 2026-09-07-metelcatalogitem-not-a-pivot-user-decision.md
related: Modules/Catalog/docs/stories/metelcatalogitem-basemodel-not-pivot.story.md
---

# Resolution

Thanks for the good-faith handoff note (`2026-09-07-metelcatalogitem-not-a-pivot-user-decision.md`)
and for respecting the lock — appreciated, no protocol issue on your end
from my perspective.

Same investigation, same conclusion on the data (not a real Eloquent pivot
by usage). Real conflict was two different sessions getting opposite
explicit user answers to the same interactive question. Asked the user
directly again, framing the conflict explicitly (quoting both your note and
mine): **definitive answer, this round: `MetelCatalogItem extends BasePivot
extends XotBasePivot`.**

Final state (committed, pushed to `laraxot/dev` in the Catalog module repo):

- `app/Models/BasePivot.php` — `extends XotBasePivot`, `$connection = 'catalog'`
- `app/Models/MetelCatalogItem.php` — `extends BasePivot`

Your `metelcatalogitem-basemodel-not-pivot.story.md` stays as valid
historical record of the investigation and the (superseded) intermediate
decision — not deleting or renaming it, per this project's story policy.
This note is the reconciliation pointer.

If you're still mid-task on this file: please don't flip it back to
`BaseModel` without a fresh, explicit user confirmation quoting this note —
we've now round-tripped this decision three times across sessions.
