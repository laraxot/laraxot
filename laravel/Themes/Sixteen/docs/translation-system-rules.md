# Translation System Rules - CRITICAL RULES

## ❌ ERRORE GRAVE COMMESSO
Ho erroneamente cambiato chiavi di traduzione strutturate in stringhe dirette.

**SBAGLIATO:**
```php
// DA: {{ __('pub_theme::auth.login.submit') }}
// A: {{ __('Accedi') }}  ❌ ERRORE GRAVE!
```

**GIUSTO:**
```php
{{ __('pub_theme::auth.login.submit') }}  ✅ CORRETTO
```

## 🎯 REGOLE FONDAMENTALI

### 1. NAMESPACE TRANSLATIONS SYSTEM
Laravel usa un sistema di namespace per le traduzioni:

```php
__('namespace::file.key.subkey')
```

**Esempi:**
- `pub_theme::auth.login.submit` → `/lang/it/auth.php` → `['login' => ['submit' => 'Accedi']]`
- `user::profile.settings` → Modulo User → `['profile' => ['settings' => 'Impostazioni']]`

### 2. STRUTTURA CORRETTA

```
Themes/Sixteen/lang/it/
├── auth.php        → pub_theme::auth.login.submit
├── ui.php          → pub_theme::ui.button.save
├── messages.php    → pub_theme::messages.success
└── validation.php  → pub_theme::validation.required
```

### 3. MAI CAMBIARE LE CHIAVI - CREARE LE TRADUZIONI

**❌ SBAGLIATO - Cambiare la chiave:**
```php
// Da: {{ __('pub_theme::auth.login.remember') }}
// A:  {{ __('Ricordami') }}  ← ERRORE!
```

**✅ GIUSTO - Creare la traduzione:**
```php
// Mantenere: {{ __('pub_theme::auth.login.remember') }}
// Creare: lang/it/auth.php → ['login' => ['remember' => 'Ricordami']]
```

### 4. PERCHÉ È IMPORTANTE

1. **Organizzazione**: Raggruppa traduzioni logicamente
2. **Manutenibilità**: Facile trovare e aggiornare traduzioni
3. **Modularità**: Ogni modulo/tema ha le sue traduzioni
4. **Consistenza**: Standard Laravel
5. **Sviluppo Team**: Altri sviluppatori sanno dove cercare

### 5. DEBUGGING TRADUZIONI

```bash
# Se vedi chiavi grezze tipo "pub_theme::auth.login.submit"
# NON cambiare la chiave → CREARE il file traduzione!

# Controlla se esiste:
ls Themes/Sixteen/lang/it/auth.php

# Se non esiste, crearlo con la struttura corretta
```

### 6. NAMESPACE PATTERN PROGETTO

```php
// Theme Sixteen
pub_theme::auth.login.submit     → Themes/Sixteen/lang/it/auth.php
pub_theme::ui.buttons.save       → Themes/Sixteen/lang/it/ui.php

// Modulo User  
user::auth.login.title           → Modules/User/lang/it/auth.php
user::profile.settings           → Modules/User/lang/it/profile.php

// Modulo Fixcity
fixcity::tickets.create          → Modules/Fixcity/lang/it/tickets.php
```

## 🚨 MEMORIA PER IL FUTURO

**QUANDO VEDI UNA CHIAVE GREZZA:**
1. ✅ Identifica namespace (pub_theme, user, fixcity)
2. ✅ Trova/crea file traduzione nel posto giusto  
3. ✅ Aggiungi la chiave con valore tradotto
4. ❌ MAI cambiare la chiave nel template

**QUESTO ERRORE NON DEVE PIÙ ACCADERE!**