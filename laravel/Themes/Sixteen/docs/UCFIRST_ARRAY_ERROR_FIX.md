# ucfirst() Array Error Fix - Header Social

> **Risolto: ucfirst(): Argument #1 ($string) must be of type string, array given**

## 📋 Panoramica

**Data:** 2026-04-01  
**Stato:** ✅ Risolto  
**Tipo:** Errore PHP 8.3 - Type mismatch  
**File:** `components/blocks/header/slim.blade.php:62`

---

## 🐛 Errore

```
TypeError: ucfirst(): Argument #1 ($string) must be of type string, array given
File: Themes/Sixteen/resources/views/components/blocks/header/slim.blade.php:62
```

---

## 🔍 Analisi

### Causa

Il blocco `header-slim` riceve `$social` come **array di array**:

```json
// JSON: tests.homepage.json
"social": [
    {"platform": "facebook", "url": "https://facebook.com"},
    {"platform": "twitter", "url": "https://twitter.com"}
]
```

**Codice SBAGLIATO:**
```blade
@foreach($social as $network)
    <a href="#" aria-label="{{ ucfirst($network) }}">
        <!-- ❌ $network è un array, NON una stringa -->
    </a>
@endforeach
```

**Problema:**
- `$network` = `['platform' => 'facebook', 'url' => '...']`
- `ucfirst($network)` ❌ Errore: array instead of string

---

## ✅ Soluzione

### Correzione

**File:** `components/blocks/header/slim.blade.php`

**Codice CORRETTO:**
```blade
@foreach($social as $network)
    @php
        $platformName = is_array($network) 
            ? ($network['platform'] ?? $network['label'] ?? 'social') 
            : $network;
        $platformUrl = is_array($network) 
            ? ($network['url'] ?? '#') 
            : '#';
    @endphp
    
    <a href="{{ $platformUrl }}" aria-label="{{ ucfirst($platformName) }}">
        <svg class="icon icon-sm">
            <use href="#it-{{ $platformName }}"></use>
        </svg>
    </a>
@endforeach
```

**Perché funziona:**
- ✅ `is_array($network)` controlla se è array o stringa
- ✅ Estrae `platform` o `label` dall'array
- ✅ Fallback a `'social'` se nessun campo trovato
- ✅ `ucfirst($platformName)` riceve SEMPRE una stringa

---

## 📊 Confronto

### PRIMA (SBAGLIATO)

```blade
@foreach($social as $network)
    <a href="#" aria-label="{{ ucfirst($network) }}">
        <!-- ❌ ucfirst() su array → TypeError -->
    </a>
@endforeach
```

**Problemi:**
- ❌ `ucfirst($network)` su array
- ❌ URL hardcoded `#`
- ❌ Nessuna verifica tipo dato

---

### DOPO (CORRETTO)

```blade
@foreach($social as $network)
    @php
        $platformName = is_array($network) 
            ? ($network['platform'] ?? $network['label'] ?? 'social') 
            : $network;
        $platformUrl = is_array($network) 
            ? ($network['url'] ?? '#') 
            : '#';
    @endphp
    
    <a href="{{ $platformUrl }}" aria-label="{{ ucfirst($platformName) }}">
        <!-- ✅ ucfirst() su stringa → OK -->
    </a>
@endforeach
```

**Vantaggi:**
- ✅ Supporta array E stringhe
- ✅ URL dinamico dal JSON
- ✅ Fallback sicuri
- ✅ PHP 8.3 compatible

---

## 🎯 Pattern Applicato

### Defensive Programming

```php
// 1. Controlla tipo dato
$platformName = is_array($network) 
    // 2. Estrai campo corretto
    ? ($network['platform'] ?? $network['label'] ?? 'social') 
    // 3. Fallback per stringhe
    : $network;

// 4. Usa variabile sicura
ucfirst($platformName);  // ✅ SEMPRE stringa
```

---

## 📚 Blocchi Affetti

### Header Slim

**File:** `components/blocks/header/slim.blade.php`

**Stato:** ✅ Corretto

---

### Footer Main

**File:** `components/blocks/footer/main.blade.php`

**Stato:** ✅ Già corretto (usava `$platform['platform']`)

```blade
@foreach($social as $platform)
    <a href="{{ $platform['url'] }}" aria-label="{{ ucfirst($platform['platform']) }}">
        <!-- ✅ Già corretto -->
    </a>
@endforeach
```

---

## 🧪 Test Cases

### Case 1: Social come Array (CORRETTO)

```json
{
    "social": [
        {"platform": "facebook", "url": "https://facebook.com"},
        {"platform": "twitter", "url": "https://twitter.com"}
    ]
}
```

**Risultato:**
```html
<a href="https://facebook.com" aria-label="Facebook">
    <svg><use href="#it-facebook"></use></svg>
</a>
```

---

### Case 2: Social come Stringhe (RETROCOMPATIBILITÀ)

```json
{
    "social": ["facebook", "twitter", "instagram"]
}
```

**Risultato:**
```html
<a href="#" aria-label="Facebook">
    <svg><use href="#it-facebook"></use></svg>
</a>
```

---

### Case 3: Social Misto (EDGE CASE)

```json
{
    "social": [
        {"platform": "facebook", "url": "https://facebook.com"},
        "twitter",
        {"label": "youtube", "url": "https://youtube.com"}
    ]
}
```

**Risultato:**
```html
<a href="https://facebook.com" aria-label="Facebook">...</a>
<a href="#" aria-label="Twitter">...</a>
<a href="https://youtube.com" aria-label="Youtube">...</a>
```

---

## ✅ Checklist Verifica

### Per Blocchi con Dati JSON

```markdown
## Tipo Dati
- [ ] Verifica struttura dati JSON
- [ ] Controlla se array o stringa
- [ ] Gestisci entrambi i casi

## Sicurezza
- [ ] Usa is_array() per controlli
- [ ] Estrai campi corretti
- [ ] Fallback sicuri

## PHP 8.3 Compatibility
- [ ] ucfirst() riceve stringa
- [ ] count() riceve array/countable
- [ ] Type hints corretti

## Test
- [ ] Test con array
- [ ] Test con stringhe
- [ ] Test con dati misti
- [ ] Test senza dati
```

---

## 🔗 Documenti Correlati

- [Blocks Implementation](./BLOCKS_IMPLEMENTATION.md) - Documentazione blocchi
- [Homepage 404 Fix](./HOMEPAGE_404_FIX.md) - Risoluzione errori homepage
- [Component Namespace](./architecture/component-namespace.md) - Namespace componenti

---

## 🎓 Lezioni Apprese

### 1. PHP 8.3 Type Safety

```php
// PHP 8.3 è più rigoroso sui tipi

// ❌ PRIMA (funzionava in PHP 8.1)
ucfirst($array);  // Warning

// ❌ ADESSO (PHP 8.3)
ucfirst($array);  // TypeError!

// ✅ SOLUZIONE
ucfirst((string)$value);  // Type casting esplicito
```

---

### 2. JSON Data Structures

```json
// I dati JSON possono avere strutture diverse

// Struttura 1: Array di oggetti
"social": [
    {"platform": "facebook", "url": "..."}
]

// Struttura 2: Array di stringhe
"social": ["facebook", "twitter"]

// Struttura 3: Mista
"social": [
    {"platform": "facebook"},
    "twitter"
]

// SOLUZIONE: Gestisci TUTTI i casi
is_array($data) ? $data['field'] : $data
```

---

### 3. Defensive Programming

```php
// MAI assumere il tipo di dato

// ❌ ASSUNZIONE (pericoloso)
ucfirst($network);  // Assume stringa

// ✅ DIFENSIVO (sicuro)
$platformName = is_array($network) 
    ? ($network['platform'] ?? 'social') 
    : $network;
ucfirst($platformName);  // SEMPRE sicuro
```

---

## ✅ Stato Attuale

**Errore:** ✅ **RISOLTO**  
**File:** ✅ **CORRETTO**  
**Test:** ✅ **PASSATI**  
**PHP 8.3:** ✅ **COMPATIBILE**

---

**Data Risoluzione:** 2026-04-01  
**Versione:** 1.0  
**Stato:** ✅ **Risolto**

---

*"Type checking con is_array()"*  
*"Fallback sicuri per retrocompatibilità"*  
*"PHP 8.3 compatible"*
