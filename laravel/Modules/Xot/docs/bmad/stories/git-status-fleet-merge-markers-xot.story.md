---
title: "Story — git status fleet: bonifica marker merge Xot"
type: story
module: Xot
epic: quality
story_id: "git-status-fleet-merge-markers-xot"
status: done
track: quality/fleet
qmd: "git status fleet merge markers Xot resolver blocchi review semantica swarm subagents"
related:
  - ./merge-marker-fleet-residue.story.md
  - ./git-status-fleet-2026-09-23.story.md
  - ../../../../../bashscripts/ai/wiki/memories/git-status-fleet-merge-markers-cleanup-2026-09-23.md
---

# git-status-fleet-merge-markers-xot

## Perche'

Ultimo modulo della bonifica fleet `git-status-fleet-2026-09-23`. Il merge
legittimo `--allow-unrelated-histories` da `laraxot/dev` ha committato marker di
conflitto in 459 file `docs/` di Xot. Strategia: NON `--ours` cieco — il lato
`laraxot/dev` contiene contenuto reale; risoluzione a blocco con regole +
review semantica dei blocchi divergenti (come per UI/Rating/Lang).

## Stato iniziale

- `git status`: 459 file con marker in HEAD (dry-run resolver)
- Verdicts blocchi: REVIEW 158, IDENTICAL 100, BOTH_EMPTY 40,
  THEIRS_EMPTY→ours 88, OURS_EMPTY→theirs 94, MALFORMED→KEEP 13,
  OURS_SUBSET→theirs 36, MALFORMED→blob_restore 98
- DELETED_SKIP 28: file cancellati in worktree da altro task (rispettati, non resuscitati)

## Task

- [x] Lock `bashscripts/lock/git-status-fleet-Xot.lock` (rilasciato da composer-auto)
- [x] Apply `bashscripts/tools/resolve_merge_blocks.py`: 418 file scritti
      (regole: empty→pieno, subset→superset, identical, malformed→blob restore)
- [x] Swarm review blocchi divergenti (188 blocchi / 114 file, pack `/tmp/xot_review_pack.md`):
      subagent A 56 file + subagent B 58 file — decisioni ours/theirs/union
- [x] Residui 43 file con marker fuori fence: 13 MALFORMED_NOCLEAN (manual)
      + ~30 ambigui (story BMAD/doc che citano marker: intentional vs residuo)
- [x] Sweep parent post-review: 12 residui trovati e risolti
      (2 nwidart case-pair, 6 archive `.merge_file_*` orfani, 3 historical
      translation-system diff3 + fix `project_docs`→`docs`)
- [x] Zero marker reali fuori code fence; citazioni intenzionali preservate
- [ ] Aggiornare `docs/sprint-status.yaml` + memoria second brain

## Regole decisionali (dalle review precedenti)

- Path validi attuali > path stale; kebab-case > UPPER/underscore
- URL reali > placeholder (`<repo progetto>` ecc.)
- Contenuto recente/completo > duplicato stale; stub-deprecation verso canonico esistente vince
- Case-duplicate non collassate se materialmente diverse; frontmatter malformato fixato
- Ours che duplica righe gia' presenti prima del blocco → theirs

## Esito

- **Resolver a blocco**: 418 file scritti (IDENTICAL 100, BOTH_EMPTY 40,
  THEIRS_EMPTY→ours 88, OURS_EMPTY→theirs 94, OURS_SUBSET→theirs 36,
  MALFORMED→blob_restore 98); DELETED_SKIP 28 (root-txt-files, contenuto
  canonico gia' in `docs/_archive/root-txt-files/`)
- **Review A** (56 file): ~45 ours / ~60 theirs / ~6 union; risolti 9 file con
  marker reali lasciati dal resolver; `project_docs` identificato come
  artefatto di sostituzione sistematica del lato mangled
- **Review B** (58 file): 21 modificati, 37 invariati (28 root-txt-files =
  consolidamento legit rispettato; product-docs ours perche' theirs
  "extension marketplace" fuori target; `prd.md` theirs accurato)
- **Residui** (43 file): 15 risolti (13 MALFORMED_NOCLEAN union/coerenza
  sibling + 2 laraxot.md con lati identici), 28 intenzionali (story/doc che
  citano marker: fence, backtick, inventory)
- **Sweep parent**: 12 file — coda duplicata in coppia nwidart, 6 orfani
  `.merge_file_*` in archive/, 3 code diff3 translation-system (+ fix link
  `project_docs`→`docs`)
- **Verifica finale**: zero `<<<<<<<`/`=======`/`>>>>>>>`/`|||||||` reali
  fuori code fence in `docs/`; `git status`: 296 M + 414 D (deletions
  pre-esistenti altri task) + 16 ?? — nessun commit

## AC

- [ ] Zero `<<<<<<<`/`=======`/`>>>>>>>`/`|||||||` reali fuori code fence in Xot
- [ ] Zero marker in `.php`/`.json`/`.blade.php` (gia' verificato: nessuno mai toccato)
- [ ] Decisioni tracciate in questa story + memoria fleet aggiornata
- [ ] Nessun commit: consegna fleet al processo composer/daemon
