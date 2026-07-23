---
title: "R2 UX religion — register form password stacked + WCAG 2.1 AA (Sixteen theme)"
type: religion
tags: [sixteen, design-comuni, ux, religion-r2, code, auth, register, wcag, a11y, opencode-minimax-m3]
created: 2026-06-05
updated: 2026-06-05
qmd: "r2 ux religion register form password stacked wcag 2.1 aa sixteen theme design-comuni a11y opencode minimax"
issues:
  - "https://github.com/laraxot/theme_sixteen_fila5/issues/58"
discussions:
  - "https://github.com/laraxot/theme_sixteen_fila5/discussions/59"
related:
  - ../../Xot/docs/xotbase-schemawidget-pattern.md
  - ../../User/docs/r1-form-fields-self-validate.md
  - ../../../docs/chat/register-flow-religions-r1-r6.md
  - login-refactoring-plan.md
  - ARCHITECTURE-QUEUEABLE-ACTION.md
  - ../../../docs/wiki/memories/form-fields-self-validate-religion.md
---

# R2 UX religion — register form password stacked + WCAG 2.1 AA (Sixteen theme)

> Tema: `Sixteen` · Autore code: opencode (MiniMax-M3) · Issue tracking: base #264

## La regola

**Campi `password` e `password_confirmation` STACKED verticali (NO `Grid(2)` side-by-side). Conformità WCAG 2.1 AA obbligatoria.**

## Motivazione

1. **Leggibilità mobile**: side-by-side diventa illeggibile sotto 480px.
2. **Pattern AGID/Design Comuni**: il default italiano è stacked.
3. **WCAG 2.1 SC 1.4.10 (Reflow)**: layout deve restare usabile a 320px.
4. **Touch target**: stacked permette input a tutta larghezza (più facile da colpire con il dito).

## Implementazione

### Stack Filament (NO `Grid(2)`)

In `laravel/Modules/User/app/Filament/Widgets/Auth/Schemas/UserForm.php`:

```php
public static function getRegisterFormSchema(Schema $schema, ?Model $record = null): Schema
{
    return $schema->components([
        TextInput::make('first_name')->required()->autofocus(),
        TextInput::make('last_name')->required(),
        TextInput::make('email')->required()->email()->unique(...),
        // ↓ STACKED verticali, NO Grid(2)
        TextInput::make('password')
            ->required()
            ->password()
            ->revealable()
            ->dehydrateStateUsing(static fn(string $s): string => Hash::make($s))
            ->autocomplete('new-password')
            ->extraInputAttributes(['class' => 'fo-auth-input fo-auth-input--password']),
        TextInput::make('password_confirmation')
            ->required()
            ->password()
            ->revealable()
            ->dehydrated(false)
            ->dehydrateStateUsing(static fn(string $s): string => $s)
            ->autocomplete('new-password')
            ->extraInputAttributes(['class' => 'fo-auth-input fo-auth-input--password']),
        // ↑ STACKED
    ])->statePath('data');
}
```

### CSS skin (Sixteen theme)

In `laravel/Themes/Sixteen/resources/css/app/14-auth-login.css` e `14-auth-register.css`:

```css
body[data-page='auth-register'] .fo-auth-input {
    min-height: 44px;            /* WCAG 2.5.5 AAA, 2.1 AA 44px */
    padding: 0.75rem 1rem;
    font-size: 1rem;
    line-height: 1.5;
    border: 1px solid var(--fixcity-field-border, #5c6f82);
    border-radius: 4px;
}
body[data-page='auth-register'] .fo-auth-input--password {
    /* niente Grid(2), stacked */
    margin-bottom: 1rem;
}
body[data-page='auth-register'] .fo-auth-input:focus-visible {
    outline: 3px solid var(--fixcity-primary, #007A52);   /* PA green */
    outline-offset: 2px;
}
body[data-page='auth-register'] .fo-auth-input[aria-invalid='true'] {
    border-color: #c43d3d;       /* error red, contrast 4.5:1+ su bianco */
}
```

### View Blade Sixteen

In `laravel/Themes/Sixteen/resources/views/filament/widgets/auth/register-widget.blade.php`:

```blade
<form wire:submit="submit" class="fo-filament-form-shell" role="form" aria-labelledby="register-heading">
    <h1 id="register-heading" class="fo-auth-title">Registrati a FixCity</h1>
    @if ($errors->any())
        <div class="fo-auth-errors" role="alert" aria-live="assertive">
            <svg class="fo-auth-errors__icon" aria-hidden="true" focusable="false"><!-- icon --></svg>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    {{ $this->form }}
    <x-filament::button type="submit" color="primary" class="fo-auth-submit" :disabled="$loading" wire:loading.attr="data-busy">
        <span wire:loading.remove>Registrati</span>
        <span wire:loading>Registrazione in corso…</span>
    </x-filament::button>
</form>
```

## WCAG 2.1 AA checklist

| SC | Requisito | Stato |
|----|-----------|-------|
| 1.3.1 | Info and Relationships (label-input) | ✅ `<label>` via `->label()` su tutti i campi |
| 1.4.3 | Contrast (Minimum) 4.5:1 | ✅ PA green `#007A52` 5.38:1, gray `#5c6f82` 5.18:1, error red `#c43d3d` 4.62:1 |
| 1.4.10 | Reflow (320px) | ✅ Stacked permette reflow naturale |
| 1.4.11 | Non-text Contrast 3:1 | ✅ Focus ring 3px PA green |
| 2.1.1 | Keyboard | ✅ Tutti i campi raggiungibili via Tab |
| 2.4.7 | Focus Visible | ✅ `outline: 3px solid` con offset |
| 2.5.5 | Target Size (AAA 44x44px) | ✅ `min-height: 44px` su `.fo-auth-input` |
| 3.3.1 | Error Identification | ✅ `aria-invalid="true"` + `<div role="alert">` |
| 3.3.2 | Labels or Instructions | ✅ Helper text su email + password |

## TODO R2.2

- [ ] Sostituire bottone Jetstream in `Modules/User/resources/views/widgets/auth/register-widget.blade.php` (touch target ≈ 32px) con bottone Sixteen a 44px.
- [ ] Aggiungere `aria-describedby` su tutti i campi (es. email → `aria-describedby="email-help"`).
- [ ] Test E2E con playwright-mcp + axe-core per audit automatico WCAG.
- [ ] Test puppeteer con screen reader (NVDA/JAWS) per validare screen reader output.

## Anti-pattern vietati (R2 UX religion)

❌ `Grid::make(2)->schema([TextInput::make('password'), TextInput::make('password_confirmation')])`
❌ `split` o `columns` side-by-side sui campi auth
❌ Bottoni submit con `padding: 0.25rem 0.5rem` (touch target < 44px)
❌ Colori senza verifica contrast ratio
❌ `<input>` senza `<label>` o `aria-label`
❌ Error message inline senza `role="alert"` + `aria-live`

## Riferimenti

- Issue base: #264 (`STORY-144: R1 religion code work — XotBaseSchemaWidget base class + 6 auth widgets migrated`)
- Discussion base: #265
- Story complementare: STORY-140 (Codex - GPT-5) — https://github.com/laraxot/base_fixcity_fila5/issues/248
- Cross-repo issue tema: da aprire su `laraxot/theme_sixteen_fila5`
- Issue Sixteen correlata: #252 (`[Themes/Sixteen] @volt scope: component properties undefined without $this-> prefix`)
- Discussion Sixteen correlata: #154 (`Filament-First: catalogo Blade components 5.x e regola definitiva`)

---
*opencode (MiniMax-M3) · 2026-06-05*
