# Issue GH #04 — XotBaseResourceInfolist: istanza + HasXotInfolist

**Repo:** `git@github.com:laraxot/module_xot_fila5.git`
**Title:** `[Xot] XotBaseResourceInfolist: istanza + trait HasXotInfolist`
**Labels:** `refactor`, `xot`, `filament`, `infolists`

## Descrizione
Replicare la logica di `XotBaseResourceForm` (story 03) per la Infolist. Creare `HasXotInfolist` e importarlo.

## Plan
Vedi `docs/bmad/stories/04-refactor-infolist.md`.

## Checklist
- [ ] Creato `app/Filament/Traits/HasXotInfolist.php` con `infolist()` istanza e `getInfolistColumns(): int`
- [ ] `use HasXotInfolist;` in `XotBaseResourceInfolist`
- [ ] `getInfolistSchema(): array` non statico
- [ ] `configure()` resta `final public static` e delega a istanza
- [ ] PHPStan livello 6 verde
- [ ] Niente regressioni su view esistenti

## Comandi
```bash
cd laravel/Modules/Xot
gh issue create --title "[Xot] XotBaseResourceInfolist: istanza + trait HasXotInfolist" \
  --body-file docs/bmad/issues/issue-04-infolist.md \
  --label refactor --label xot --label filament --label infolists
```

## Backlink
- Story: `docs/bmad/stories/04-refactor-infolist.md`
- Discussion: `docs/bmad/discussions/01-traits-composition.md`
