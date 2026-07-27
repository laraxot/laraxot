---
title: "Git push — unpack fallito e oggetti LFS mancanti (module_ui_fila5)"
type: rule
tags: [git, lfs, push, troubleshooting, ui, no-thin, dual-remote]
created: 2026-07-08
updated: 2026-07-22
qmd: "git push LFS missing objects no-thin GH008 module UI laraxot provtv"
issues:
  - https://github.com/laraxot/module_ui_fila5/issues/24
discussions:
  - https://github.com/laraxot/module_ui_fila5/discussions/25
related:
  - "./git-merge-conflict-inventory.md"
  - "./module-theme-root-hygiene.md"
  - "../../multi-org-sync-laraxot-provtv.md"
  - "../../git-multi-org-sync-handoff.md"
  - "../../second-brain.md"
---

# Git push — unpack fallito e oggetti LFS mancanti

Canon operativo per `laravel/Modules/UI` (remote tipici: `laraxot` + `provtv` → `module_ui_fila5`).  
Stesso playbook vale per altri moduli/temi dual-remote: vedi handoff multi-org.

## Perché (bussiness logic)

- **Scopo:** pubblicare `dev` su **tutti** i remote raggiungibili senza force e senza riscrivere storia.
- **Politica:** forward-only — niente `reset`/`restore`/`push --force` su branch condivisi.
- **Filosofia:** se un remote ha già accettato il tip, gli oggetti LFS si **recuperano** da lì e si spingono sull’altro (sibling), invece di schiacciare i commit.

---

## Caso applicato 2026-07-22 (soluzione preferita)

Path: `cd laravel/Modules/UI` · branch `dev` · tip `b874935`.

### Sintomi

1. `remote: fatal: did not receive expected object …` + `unpack failed: index-pack failed`  
   (spesso con pack **thin** su storie merge/deep divergence laraxot↔provtv).
2. Su `provtv`: `GH008: Your push referenced … unknown Git LFS objects` / lista `(missing) resources/svg/...`.

### Fix eseguito (in ordine)

```bash
cd laravel/Modules/UI
git fetch laraxot dev
git fetch provtv dev

# 1) Pack completo (evita unpack “expected object” fantasma)
git -c pack.useSparse=false push --no-thin laraxot HEAD:dev
# → OK 4775de9..b874935

# 2) LFS: sibling sano → org che rifiuta
git lfs fetch laraxot --all
git lfs push provtv --all

# 3) Stesso push no-thin verso provtv
git -c pack.useSparse=false push --no-thin provtv HEAD:dev
# → OK dfbb830..b874935
```

### Esito

| Remote | Prima | Dopo |
|--------|-------|------|
| `laraxot/dev` | ahead locale | `b874935` (FF) |
| `provtv/dev` | blocco LFS GH008 | `b874935` (FF + LFS allineato) |

Niente squash, niente force, niente rewrite.

### Diagnosi rapida

```bash
git rev-list --left-right --count HEAD...laraxot/dev
git rev-list --left-right --count HEAD...provtv/dev
git merge-base --is-ancestor laraxot/dev HEAD && echo "FF ok laraxot"
# se unpack fallisce anche con oggetti locali presenti → --no-thin
# se GH008 → lfs fetch --all dal remote che ha già il tip, poi lfs push --all
```

---

## Caso storico 2026-07-08 (LFS irrecuperabile)

Quando **nessun** remote/clone ha gli OID LFS (404 ovunque) e i puntatori nello storico sono orfani, in passato si era usato uno squash sopra `laraxot/dev`.

**Oggi non è il default:** viola lo spirito forward-only se implica `reset --soft`. Preferire sempre:

| Scenario | Azione |
|----------|--------|
| Unpack / `expected object` | `git push --no-thin` (+ rete non sandboxata) |
| GH008 / LFS missing, sibling OK | `git lfs fetch <sibling> --all` → `git lfs push <target> --all` → push |
| LFS recuperabile da altro clone | copiare `.git/lfs/objects/` o `lfs push --all` dal clone sano |
| LFS irrecuperabile + storia da sanare | solo con decisione umana esplicita; documentare; **mai** force su `main`/`master` |

### Cosa **non** fare

- `git config lfs.allowincompletepush true` — clone rotti per gli altri.
- Force push su branch condivisi.
- `git reset --soft` / `git restore` per “aggiustare” il push (forward-only).
- Reintrodurre LFS su `*.svg` / `*.png` senza policy e storage affidabile.

## Prevenzione

1. SVG/PNG piccoli → blob Git normali; LFS solo se davvero necessario.
2. Prima di push massivi: `git lfs push --dry-run <remote> dev`.
3. Dual-remote: push FF a **entrambi**; se uno fallisce, non lasciare i due tip divergenti.
4. Messaggi commit descrittivi (evitare catene di `.`).

## Checklist post-push (modulo UI)

```bash
cd laravel/Modules/UI
git status --short --branch   # ideale: ## dev...laraxot/dev (0 0)
# nessun import Geo attivo
grep -r "Modules\\\\Geo" app/ --include="*.php" | grep -v '\.old' | grep -v '\.to_geo' || true
```

## Riferimenti

- Multi-org UI: [multi-org-sync-laraxot-provtv.md](../../multi-org-sync-laraxot-provtv.md)
- Handoff: [git-multi-org-sync-handoff.md](../../git-multi-org-sync-handoff.md)
- Second brain: [second-brain.md](../../second-brain.md)
- Forward-only progetto: [../../../../../../docs/wiki/rules/git-forward-only.md](../../../../../../docs/wiki/rules/git-forward-only.md)
- Issue LFS: [laraxot/module_ui_fila5#24](https://github.com/laraxot/module_ui_fila5/issues/24)
