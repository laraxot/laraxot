# Guida agli Errori Page Route nelle Classi Filament XotBase

## ⚠️ PROBLEMA CRITICO IDENTIFICATO

### **Errore Frequente**
```
BadMethodCallException
Method Modules\Notify\Filament\Resources\NotificationTemplateResource\Pages\PreviewNotificationTemplate::route does not exist.
```

### **Causa**
Quando si estende `XotBasePage` e si utilizza il metodo `route()` per registrare pagine personalizzate nei Resource, il metodo deve essere disponibile nella classe base.

## 📋 Metodi Richiesti per Classe

### **XotBasePage**
```php
/**
 * Create a route for this page.
 * 
 * @param string $path
 * @return string
 */
public static function route(string $path): string
{
    return static::class . '::' . $path;
}
```

## 🔧 Soluzione Implementata

### **File Aggiornato**
- ✅ `laravel/Modules/Xot/app/Filament/Resources/Pages/XotBasePage.php`
- ✅ Aggiunto metodo `route()` statico
- ✅ Tipizzazione completa con PHPDoc

### **Utilizzo Corretto**
```php
// In NotificationTemplateResource.php
public static function getPages(): array
{
    return [
        ...parent::getPages(),
        'preview' => Pages\PreviewNotificationTemplate::route('/{record}/preview'),
    ];
}
```

## 📝 Pattern di Implementazione

### **Estensione Corretta**
```php
<?php

declare(strict_types=1);

namespace Modules\Notify\Filament\Resources\NotificationTemplateResource\Pages;

use Modules\Xot\Filament\Resources\Pages\XotBasePage;
use Modules\Notify\Filament\Resources\NotificationTemplateResource;

class PreviewNotificationTemplate extends XotBasePage
{
    protected static string $resource = NotificationTemplateResource::class;
    
    protected static string $view = 'notify::filament.resources.notification-template-resource.pages.preview-notification-template';
    
    // CORRETTO: Il metodo route() è ora disponibile tramite XotBasePage
}
```

## 🎯 Checklist Risoluzione

- [ ] La classe estende `XotBasePage`
- [ ] `XotBasePage` ha il metodo `route()` implementato
- [ ] Il metodo `route()` è statico e pubblico
- [ ] La tipizzazione è corretta (`string` → `string`)
- [ ] PHPDoc completo per il metodo
- [ ] Il Resource utilizza correttamente `::route()`

## 🔗 Classe e Metodi Correlati

### **Base Classes**
- `XotBasePage` → Fornisce il metodo `route()`
- `XotBaseResource` → Utilizza il metodo `route()` in `getPages()`

### **Metodi Statici Filament**
- `route(string $path): string` → Crea rotte per pagine personalizzate
- `getPages(): array` → Registra le pagine del Resource

## 📚 Documentazione Correlata

- [XotBase Extension Rules](xotbase_extension_rules.md)
- [Abstract Methods Guide](filament_abstract_methods_guide.md)
- [Model Inheritance Best Practices](model_inheritance_best_practices.md)

---

**Risolto il**: $(date +%Y-%m-%d)  
**Versione**: Laraxot <nome progetto> v2.0  
**Moduli Interessati**: Xot, Notify
