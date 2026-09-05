# Miglioramenti Futuri: Modulo Xot

Visione a lungo termine per il framework-level di Laraxot.

## V2: Stratificazione Base Class (6-12 mesi)

Attualmente tutti gli `XotBase*` sono monolitici. Domanda: quale livello di astrazione serve?

**Problema:** `XotBaseResource` contiene logica UI, template, validazione, authorization. Troppo.

**Visione:** Stratificazione per layer
- `XotBaseResource` → UI-specific (form(), table(), infolist())
- `XotBasePersistence` → Data model (model, factory, migration)
- `XotBaseAuthorization` → Policy enforcement
- `XotBaseComputation` → Business logic (Actions)

**Impatto:** 
- Moduli piccoli possono ereditare solo `XotBasePersistence` (no UI overhead)
- UI modules customizzano senza toccare data layer
- Testing semplificato: test persistence ≠ test UI

**Decisione in sospeso:** Questa stratificazione è smart o over-engineering?

---

## V2: Discovery Autodidatta (3-6 mesi)

Attualmente discovery avviene via convention (model path → resource path). Rigido.

**Problema:** Nuovo pattern UI = nuova convention. Filament cambia API = break discovery.

**Visione:** Metadata-driven discovery
```php
// IN MODELLO
class User extends XotBaseModel {
    #[UiResource(
        forms: ['\Modules\User\Filament\Forms\UserForm'],
        tables: ['\Modules\User\Filament\Tables\UserTable'],
    )]
    public function uiResources() { }
}
```

**Impatto:**
- Zero convention; UI resources espliciti
- Filament version upgrade non break discovery
- Moduli customizzati facilmente (multi-resource per model)

**Trade-off:** Boilerplate extra vs flessibilità.

---

## V2: Validation Layer Shared (6 mesi)

Attualmente Form validation è in Filament Form schema. Hard to reuse in API.

**Visione:** Estrai validation rules in shared `Contracts\Validatable`
```php
class User extends XotBaseModel implements Validatable {
    public static function rules(): array {
        return [
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:12', 'current_password'],
        ];
    }
}
```

**Benefici:**
- Form validation = API validation (DRY)
- Change rule once, everywhere aggiornato
- Type-safe validation (no string rules)

**Challenge:** Rules per context (create vs update, admin vs user)

---

## V3: Modular Testing Framework (12+ mesi)

Attualmente no built-in test infrastructure. Ogni modulo reimplementa fixtures, factories.

**Visione:** Xot-provided testing foundation
- `XotBaseTestCase` → setUp tenant, auth, seeders
- `XotTestFactory` → generate valid models quick
- `XotResponseAssertions` → common assertions (can('view'), hasErrors, etc)

**Impatto:**
- Nuovo modulo dev = test-first auto
- Coverage tracciato per modulo
- Test failures = immediate red flag

**Prerequisito:** Zero circular test dependencies

---

## False Friends to Avoid

- **"More base classes = more reusable"** → NO: each layer adds coupling. Stratificare, non accumulare
- **"Convention > Configuration"** → Convenzione riduce boilerplate ma rigida. Metadata flesso, convention quando possibile
- **"Validation in Form = DRY"** → NO: form è UI, validation è business rule. Estrai a modello
- **"Shared test infrastructure = less work"** → NO: test fixtures spesso domain-specific. Fornisci utilities, non framework

---

## Vedi Anche

- `PHILOSOPHY.md` — regole fondamentali
- `ARCHITECTURE.md` — design attuale
- `../../../bmad-output/module-philosophy-audit.story.md` — audit complete
