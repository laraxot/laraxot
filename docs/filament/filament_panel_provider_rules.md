# Regole per Panel Provider di Filament in Laraxot

## Regola Fondamentale: Ereditarietà Panel Provider

**CRITICO**: Il file `laravel/app/Providers/Filament/AdminPanelProvider.php` deve **SEMPRE** estendere `Modules\Xot\Providers\Filament\XotBaseMainPanelProvider`, **MAI** direttamente `Filament\PanelProvider`.

### ✅ Pattern Corretto

```php
<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use Modules\Xot\Providers\Filament\XotBaseMainPanelProvider;

class AdminPanelProvider extends XotBaseMainPanelProvider
{
    // Implementazione specifica se necessaria
}
```

### ❌ Anti-pattern (MAI USARE)

```php
<?php

namespace App\Providers\Filament;

use Filament\PanelProvider;

class AdminPanelProvider extends PanelProvider
{
    // ERRORE: Non estende XotBaseMainPanelProvider
}
```

## Motivazione

1. **Centralizzazione**: XotBaseMainPanelProvider contiene tutta la configurazione base di Filament
2. **Coerenza**: Garantisce che tutti i progetti Laraxot abbiano la stessa configurazione di base
3. **Manutenibilità**: Le modifiche alla configurazione base vengono applicate automaticamente
4. **Funzionalità**: Include middleware, autenticazione, navigazione e altre funzionalità core

## Struttura XotBaseMainPanelProvider

Il `XotBaseMainPanelProvider` include automaticamente:

- Configurazione panel con ID 'admin' e path '/admin'
- Middleware di autenticazione e sicurezza
- Configurazione SPA (Single Page Application)
- Sidebar collassabile
- Reset password
- Profilo utente
- Scoperta automatica di risorse, pagine e widget
- Navigazione modulare
- Menu utente con profilo

## Best Practices

1. **SEMPRE** estendere `XotBaseMainPanelProvider`
2. **MAI** reimplementare funzionalità già presenti nella classe base
3. **SEMPRE** chiamare `parent::panel()` se si sovrascrive il metodo
4. **SEMPRE** aggiungere `declare(strict_types=1);`
5. **SEMPRE** documentare personalizzazioni specifiche

## Errori Comuni

### Errore: Class not found
```
Class "Modules\Xot\Providers\Filament\XotBaseMainPanelProvider" not found
```

**Soluzioni**:
1. Verificare che il file `XotBaseMainPanelProvider.php` esista in `Modules/Xot/app/Providers/Filament/`
2. Eseguire `composer dump-autoload`
3. Verificare che il modulo Xot sia attivo
4. Controllare che il namespace sia corretto

### Errore: Funzionalità mancanti
Se mancano funzionalità come autenticazione o navigazione, verificare che si stia estendendo la classe corretta.

## Checklist Implementazione

- [ ] AdminPanelProvider estende XotBaseMainPanelProvider
- [ ] Include `declare(strict_types=1);`
- [ ] Namespace corretto: `App\Providers\Filament`
- [ ] Import corretto: `use Modules\Xot\Providers\Filament\XotBaseMainPanelProvider;`
- [ ] Non reimplementa funzionalità base
- [ ] Documentazione aggiornata

## Collegamenti

- [Modules/Xot/docs/PANEL_PROVIDER.md](mdc:../../laravel/Modules/Xot/docs/PANEL_PROVIDER.md)
- [docs/FILAMENT_BEST_PRACTICES.md](mdc:../../docs/FILAMENT_BEST_PRACTICES.md)
- [docs/ARCHITECTURE.md](mdc:../../docs/ARCHITECTURE.md)

*Ultimo aggiornamento: 2025-01-06* 