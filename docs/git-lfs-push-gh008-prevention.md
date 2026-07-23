# Prevenzione errore Git push GH008 (Git LFS oggetto mancante)

## Sintomo

```text
remote: error: GH008: Your push referenced at least 1 unknown Git LFS object
remote:     573599f8f4571c499396289ea0f32e5fb50a6f059d2f4cd16e79dc8428cef700
! [remote rejected] master -> master (pre-receive hook declined)
```

Spesso compare dopo merge/commit massivi che includono artefatti di conflitto con nomi tipo `.ai~cc6378f (.)`.

## Causa root

1. Durante risoluzione conflitti Git crea **copie di backup** dei symlink agent (`.ai`, `.claude`, `.cursor`, …) con suffisso `~cc6378f`.
2. In `Modules/Notify/.gitattributes` i file `*.psd` usano LFS; un puntatore LFS può finire su un backup symlink di 18 byte.
3. Il commit contiene il **puntatore LFS** ma **non il blob** → GitHub rifiuta il push.

## Diagnosi rapida

```bash
git lfs ls-files
git lfs status
git push origin master   # legge GH008 se manca l'oggetto
```

Se `git lfs ls-files` mostra path sospetti (`*~cc6378f*`), sono candidati alla rimozione.

## Correzione (branch locale non ancora pushato)

```bash
# Rimuovi artefatti merge dal index (nomi con spazio e "(.)")
git rm -f "laravel/Modules/Notify/.ai~cc6378f (.)" \
         "laravel/Modules/Notify/.claude~cc6378f (.)" \
         # ... tutti i *~cc6378f* nel modulo

# Verifica zero puntatori LFS orphan
git lfs ls-files

# Se l'errore era nell'ultimo commit non pushato:
git commit --amend --no-edit

git push origin master
```

## Prevenzione

1. **`.gitignore` nel modulo** — pattern già in `laravel/Modules/Notify/.gitignore`:

   ```gitignore
   *~cc6378f*
   *~cc6378f (*)
   ```

2. **Pre-push check** (manuale o CI):

   ```bash
   test "$(git lfs ls-files | wc -l)" -eq 0 || git lfs push --all origin "$(git branch --show-current)"
   find laravel/Modules -name '*~cc6378f*' -print
   ```

3. **Mai committare** symlink backup post-merge; eliminarli prima del commit:

   ```bash
   find laravel/Modules -name '*~cc6378f*' -delete
   ```

4. **Dopo merge submodule/moduli**: controllare `git status` per file con ` (.)` nel nome.

## Caso risolto (2026-06-06, master)

- Commit `9473b9de` bloccato su `laravel/Modules/Notify/.ai~cc6378f (.)` (LFS oid `573599f8…`).
- Rimossi 11 file `*~cc6378f*` da Notify + amend commit.
- Push `master → origin/master` OK (`35492e20`).

## Errore correlato: `ruvector.db` in git

Database vettoriali locali (~1.5 MB ciascuno) **non devono** essere versionati: gonfiano il repo e possono finire in commit massivi insieme ad artefatti merge.

### Diagnosi

```bash
git ls-files | grep ruvector.db
```

Se compare output → rimuovere dal tracking (file resta su disco):

```bash
git rm --cached -f $(git ls-files | grep ruvector.db)
```

### Prevenzione

Root `.gitignore`:

```gitignore
**/ruvector.db
ruvector.db
*~cc6378f*
*~cc6378f (*)
```

Ogni modulo/tema può aggiungere `ruvector.db` nel proprio `.gitignore`.

## Checklist pre-push (master / dev)

Eseguire **prima** di `git push`:

```bash
# 1. Nessun puntatore LFS orphan
git lfs ls-files
# atteso: vuoto (o oggetti presenti localmente → git lfs push --all)

# 2. Nessun backup merge symlink
find laravel/Modules laravel/Themes -name '*~cc6378f*' 2>/dev/null

# 3. Nessun ruvector.db tracciato
git ls-files | grep ruvector.db || true

# 4. Push
git push origin "$(git branch --show-current)"
```

Se (1) o (3) falliscono → **non pushare** finché non si fa `git rm --cached` / amend del commit locale.

## Perché succede (filosofia)

- Il tema e la config tenant sono **vestiti** configurabili; i symlink agent (`.ai`, `.cursor`, …) sono **locali**.
- I backup `~cc6378f` nascono da merge submodule — Git li tratta come file normali; se matchano LFS (`*.ai` in `.gitattributes` di altri path) il puntatore va su GitHub **senza blob** → GH008.
- **Regola**: dopo ogni merge, `git status` + eliminare `*~cc6378f*` prima del commit.

## Collegamenti

- [pub_theme_namespace_rule.md](./pub_theme_namespace_rule.md) — tema FO TechPlanner
- [theme-view-resolution-solution.md](./theme-view-resolution-solution.md)
- [project_docs/git-conflict-resolution/README.md](./project_docs/git-conflict-resolution/README.md)
