# 🚨 CRITICAL RULES - Regole Critiche del Progetto

**Last Update**: 2025-12-09
**Importance**: MASSIMA PRIORITÀ
**Enforcement**: AUTOMATICO

---

## ⛔ REGOLA 1: NO MERGE CONFLICTS NEI FILE .md

### Descrizione
**NESSUN file .md deve MAI contenere marker di merge conflicts.**

### Marker Vietati
```
```

### Enforcement
```bash
# Verifica automatica
grep -r "<&lt;&lt;&lt; HEAD" --include="*.md" .

# Output atteso: nessun risultato
# Se trova file: BLOCCARE TUTTO e risolvere immediatamente
```

### Come Risolvere
1. **Identificare il file** con conflict
2. **Leggere entrambe le versioni** (HEAD e branch)
3. **Scegliere la versione corretta** o unire manualmente
4. **Rimuovere TUTTI i marker** (`<<<<<<<`, `=======`, `>>>>>>>`)
5. **Verificare sintassi markdown** del file risultante
6. **Committare la risoluzione**

### Perché è Critica
- ❌ **Rompe la documentazione**: File illeggibili
- ❌ **Confonde sviluppatori**: Non si capisce qual è la versione corretta
- ❌ **Blocca CI/CD**: Build failures
- ❌ **Perde informazioni**: Versioni incomplete

### Prevenzione
- ✅ Usare `git pull --rebase` invece di `git pull`
- ✅ Risolvere conflicts SUBITO, non commitarli mai
- ✅ Verificare con `git status` prima di commit
- ✅ Hook pre-commit che blocca se trova conflicts

---

## ⛔ REGOLA 2: NO property_exists() con Modelli Eloquent

### Descrizione
**MAI usare `property_exists()` con modelli Eloquent perché gli attributi sono magici.**

### Codice Vietato
```php
// ❌ SBAGLIATO - NON funziona con Eloquent
if (property_exists($model, 'email')) {
    $email = $model->email;
}
```

### Codice Corretto
```php
// ✅ CORRETTO - Usa isset() con __isset() magic method
if (isset($model->email)) {
    $email = $model->email;
}

// ✅ ALTERNATIVA - Usa hasAttribute() (Laravel 10+)
if ($model->hasAttribute('email')) {
    $email = $model->email;
}

// ✅ ALTERNATIVA - Usa array_key_exists su attributi
if (array_key_exists('email', $model->getAttributes())) {
    $email = $model->email;
}
```

### Perché è Critica
Eloquent usa **magic properties** tramite `__get()` e `__set()`:
- Gli attributi NON sono properties PHP reali
- `property_exists()` controlla solo properties dichiarate nella classe
- Gli attributi del database sono accessibili tramite magic methods
- `isset()` chiama `__isset()` che controlla correttamente gli attributi

### Esempio Pratico
```php
class User extends Model
{
    // ❌ NESSUNA property $email dichiarata qui

    protected $fillable = ['name', 'email'];

    public function example(): void
    {
        // ❌ SBAGLIATO - Sempre false!
        $exists = property_exists($this, 'email'); // false

        // ✅ CORRETTO - Verifica attributo magico
        $exists = isset($this->email); // true se presente nel DB
    }
}
```

### Come Trovare Violazioni
```bash
# Cerca property_exists in Models
grep -r "property_exists" Modules/*/app/Models/ --include="*.php"

# Output atteso: nessun risultato
# Se trova file: CORREGGERE immediatamente
```

### Reference
- [Laravel Eloquent Magic Methods](https://laravel.com/docs/12.x/eloquent#retrieving-models)
- [PHP Magic Methods](https://www.php.net/manual/en/language.oop5.magic.php)
- [UI Module Documentation](./Modules/UI/docs/eloquent-properties-isset-vs-property-exists.md)

---

## ⛔ REGOLA 3: NO Modifiche a phpstan.neon

### Descrizione
**VIETATO modificare `phpstan.neon` per ignorare errori. Tutti gli errori vanno corretti.**

### File Intoccabile
```
laravel/phpstan.neon
```

### Vietato
```yaml
# ❌ NON FARE MAI QUESTO
parameters:
    ignoreErrors:
        - '#Cannot access property#'
        - '#Method not found#'
```

### Approccio Corretto
- ✅ **Correggere il codice sorgente**, non la configurazione
- ✅ **Type narrowing** con `is_string()`, `is_array()`, ecc.
- ✅ **Assert** con Webmozart\Assert
- ✅ **Cast Actions** centralizzate (`SafeStringCastAction`, ecc.)
- ✅ **PHPDoc** espliciti con generics

---

## ⛔ REGOLA 4: NO mixed Type (Ultima Risorsa)

### Descrizione
**Usare `mixed` SOLO come ultima spiaggia quando non si può determinare il tipo.**

### Gerarchia di Preferenza
1. ✅ **Tipo specifico**: `string`, `int`, `array<string, mixed>`
2. ✅ **Union type**: `string|int`, `array|null`
3. ✅ **Nullable**: `?string`, `?int`
4. ⚠️ **mixed**: Solo se IMPOSSIBILE determinare

### Esempio
```php
// ❌ SBAGLIATO - Mixed non necessario
public function getData(): mixed
{
    return $this->data;
}

// ✅ CORRETTO - Tipo specifico
/**
 * @return array<string, mixed>
 */
public function getData(): array
{
    Assert::isArray($this->data);
    return $this->data;
}
```

---

## ⛔ REGOLA 5: Nomi File .md Standardizzati

### Descrizione
**I nomi dei file .md devono seguire convenzioni precise.**

### Convenzioni
- ✅ **kebab-case**: `my-document-name.md`
- ✅ **Eccezione**: `README.md` (maiuscolo)
- ❌ **NO date**: `document-2025-12-09.md` (VIETATO)
- ❌ **NO maiuscole**: `MyDocument.md` (VIETATO)
- ❌ **NO underscore**: `my_document.md` (preferisci kebab-case)

### Esempi
```
✅ phpstan-fixes.md
✅ business-logic-overview.md
✅ README.md
✅ architecture-overview.md

❌ PHPStanFixes.md (maiuscole)
❌ business_logic_overview.md (underscore non necessari)
❌ fixes-2025-12-09.md (date vietate)
❌ BusinessLogic.md (PascalCase vietato)
```

### Verifica Duplicati
Prima di creare un nuovo file .md:
```bash
# Cerca file simili
find Modules/*/docs -name "*keyword*" -type f

# Evita duplicati con nomi diversi
```

---

## ⛔ REGOLA 6: Dopo Ogni Modifica = Verifica Completa

### Descrizione
**Ogni modifica a un file deve essere seguita da verifica con tutti gli strumenti.**

### Workflow Obbligatorio
```bash
# 1. Modifica il file
vim Modules/Geo/app/Actions/GetCoordinatesAction.php

# 2. Verifica PHPStan (folder completa del file)
./vendor/bin/phpstan analyse Modules/Geo --level=10 --memory-limit=-1

# 3. Verifica PHPMD
./vendor/bin/phpmd Modules/Geo text cleancode,codesize,controversial,design,naming,unusedcode

# 4. Verifica PHP Insights
./vendor/bin/phpinsights analyse Modules/Geo --min-quality=90

# 5. Aggiorna documentazione
vim Modules/Geo/docs/phpstan-fixes.md
```

### Non Procedere Se
- ❌ PHPStan segnala errori
- ❌ PHPMD segnala problemi critici
- ❌ PHP Insights score < 90%
- ❌ Documentazione non aggiornata

---

## ⛔ REGOLA 7: Cartelle docs = Memoria Permanente

### Descrizione
**Le cartelle `docs/` sono la memoria del progetto. SEMPRE aggiornate.**

### Obblighi
1. ✅ **Prima di modificare**: Studiare `Modules/{Module}/docs/`
2. ✅ **Durante modifica**: Prendere appunti delle decisioni
3. ✅ **Dopo modifica**: Aggiornare docs con:
   - Pattern applicati
   - Errori risolti
   - Decisioni architetturali
   - Link a riferimenti

### Struttura Standard
```
Modules/{Module}/docs/
├── README.md                    # Overview del modulo
├── phpstan-fixes.md             # Storia correzioni PHPStan
├── architecture/                # Architettura
├── business/                    # Business logic
└── troubleshooting/             # Problemi comuni
```

---

## 🎯 Enforcement Automatico

### Pre-Commit Hook
```bash
#!/bin/bash
# .git/hooks/pre-commit

# Verifica merge conflicts
if grep -r "<&lt;&lt;&lt; HEAD" --include="*.md" .; then
    echo "❌ ERRORE: File .md contiene merge conflicts!"
    exit 1
fi

# Verifica property_exists in Models
if grep -r "property_exists" Modules/*/app/Models/ --include="*.php"; then
    echo "❌ ERRORE: property_exists() usato in Models!"
    exit 1
fi

echo "✅ Verifiche superaterun PHPStan again.


```

### CI/CD Pipeline
```yaml
# .github/workflows/quality.yml
name: Code Quality

on: [push, pull_request]

jobs:
  critical-rules:
    runs-on: ubuntu-latest
    steps:
      - name: Check merge conflicts
        run: |
          ! grep -r "<&lt;&lt;&lt; HEAD" --include="*.md" .

      - name: Check property_exists
        run: |
          ! grep -r "property_exists" Modules/*/app/Models/ --include="*.php"

      - name: PHPStan
        run: ./vendor/bin/phpstan analyse Modules --level=10
```

---

## 📚 Reference

- [DOCUMENTATION_INDEX.md](./DOCUMENTATION_INDEX.md) - Mappa completa documentazione
- [DOCS_README.md](./DOCS_README.md) - Quick start documentazione
- [CLAUDE.md](./CLAUDE.md) - Regole architetturali progetto
- [Modules/_DOCS_TEMPLATE/](./Modules/_DOCS_TEMPLATE/) - Template documentazione

---

**RICORDA**: Queste regole NON sono opzionali. Sono CRITICHE per la salute del progetto.

**Mantra**: "Fix, Don't Ignore. Document, Don't Forget. Enforce, Don't Bypass."

---

**Version**: 1.0
**Last Review**: 2025-12-09
**Next Review**: Ogni 3 mesi o dopo incident critici

## 15. MANDATORY Post-Modification Quality Checks

**After modifying ANY file, you MUST run quality checks on its directory:**

1. **PHPStan Level 10**: `./vendor/bin/phpstan analyse path/to/dir --level=10`
2. **PHPMD**: `./vendor/bin/phpmd path/to/dir text phpmd.ruleset.xml`
3. **PHPInsights**: `./vendor/bin/phpinsights analyse path/to/dir`

**NEVER skip this step.**