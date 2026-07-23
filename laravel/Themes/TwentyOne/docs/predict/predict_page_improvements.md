# Miglioramento pagina `predicts/[slug].blade.php` – Theme *TwentyOne*

> Questa analisi replica e adatta quanto già redatto nel modulo `Predict` aggiungendo considerazioni specifiche per il tema *TwentyOne* (design-system, variabili CSS, dark-mode, componenti Blade del tema).

## 1. Stato attuale (Theme Perspective)
| Aspetto | Valutazione | Note Theme-Specific |
| ------- | ----------- | ------------------- |
| Coerenza visiva | ⭐⭐⭐ | Utilizza palette Tailwind standard, non sfrutta variabili CSS del theme. |
| Tipografia | ⭐⭐ | Non applica font-stack di TwentyOne (`--font-sans`, `--font-heading`). |
| Dark-mode | ⭐ | Manca supporto class `dark:` definito in ThemeServiceProvider. |
| Componenti riusabili | ⭐⭐ | Layout generico `x-layouts.app`, non usa componenti macro `x-tw-card`, `x-tw-breadcrumb`. |

## 2. Confronto con top prediction-market sites (breve)
- **Polymarket**: UI minimal, high-contrast dark theme → compatibile con palette `slate + indigo` di TwentyOne.
- **Manifold**: Animazioni sottili, badge colori saturi → usare helpers `@tw-badge`.

## 3. Proposte di miglioramento – Theme Integration
1. **Adottare component library di TwentyOne**
   ```blade
   <x-tw-card shadow="md" class="p-6">
       @livewire(...)
   </x-tw-card>
   ```
2. **Palette & CSS vars**
   - Sostituire colori hard-coded con `var(--twc-primary-500)` ecc.
3. **Dark-mode ready**
   - Aggiungere classi `dark:bg-slate-800 dark:text-slate-100`.
4. **Tipografia**
   - Usare `font-heading` per titoli e `font-sans` per paragrafi.
5. **Sticky Order Panel**
   - Nuovo componente `x-tw-sticky-panel` disponibile dal theme.

## 4. Roadmap sintetica
| Step | Task | Owner |
| ---- | ---- | ----- |
| 1 | Refactor markup con componenti `x-tw-card`, `x-tw-breadcrumb` | FE |
| 2 | Implement dark-mode classes | FE |
| 3 | Mappare colori → CSS vars theme | FE |
| 4 | Creare `OrderPanel` Volts + sticky | BE/FE |

## 5. Note tecniche
- **Service Provider**: aggiungere in `boot()` mapping viewPath del theme se sovrascriviamo la page.
- **Publishing assets**: eseguire `php artisan theme:publish TwentyOne --tag=tw-components` se necessario.

---

Per i dettagli completi vedi `Modules/Predict/docs/predict_page_improvements.md`. Questa versione enfatizza solo l'integrazione con il tema TwentyOne.
