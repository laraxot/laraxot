---
name: git-push-dual-remote-unrelated
description: provtv/dev push rejected non-fast-forward; git merge-base with local dev returns nothing (unrelated histories) — skipped, flagged for human decision
metadata:
  type: troubleshooting
---

# Git Push — provtv Unrelated History (Xot)

## Symptom

```
git push provtv dev
 ! [rejected]  dev -> dev (non-fast-forward)
```

`laraxot/dev` push succeeded cleanly (`Everything up-to-date` / fast-forward).
`provtv/dev` is ~40 commits "ahead" per `git log dev..provtv/dev`, but:

```bash
git merge-base dev provtv/dev
# (empty, exit 1)
```

No common ancestor exists between local `dev` and `provtv/dev`. This is not a
normal divergence that a `pull --rebase` or merge could safely resolve — it's
two unrelated commit graphs sharing the same branch name.

## Action taken

- Did **not** force-push, merge, or rebase.
- `laraxot` remote treated as authoritative/working remote for this module for
  now (push succeeded there).
- `provtv` push skipped and flagged here for a human decision on which
  history is canonical.

## Related

Same pattern previously confirmed in `Modules/UI` and `Modules/User`:

- `../../../User/docs/wiki/troubleshooting/git-lfs-orphan-pointer-svg-fix.md`
  (provtv-vs-laraxot unrelated-history section)
- `../../../UI/docs/wiki/troubleshooting/` (original discovery, hundreds of
  add/add conflicts on attempted rebase/merge — safely aborted both times)

Treat "provtv vs laraxot unrelated root commits" as a recurring cross-module
pattern, not a one-off. Check `git merge-base` before assuming any two-remote
sync will fast-forward or merge cleanly.
