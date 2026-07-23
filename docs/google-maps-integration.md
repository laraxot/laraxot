# Google Maps Integration - Troubleshooting

## Problema Comune: ServiceProvider Not Found

### Errore
```
Class "Cheesegrits\FilamentGoogleMaps\FilamentGoogleMapsServiceProvider" not found
```

### Causa
Il pacchetto `cheesegrits/filament-google-maps` non è installato ma Laravel cerca di caricare il ServiceProvider.

### Soluzione Rapida
```bash
composer require cheesegrits/filament-google-maps
```

## Configurazione Completa

### 1. Installazione Pacchetto
```bash
composer require cheesegrits/filament-google-maps
```

### 2. Pubblicazione Configurazione
```bash
php artisan vendor:publish --provider="Cheesegrits\FilamentGoogleMaps\FilamentGoogleMapsServiceProvider"
```

### 3. Variabili d'Ambiente
```env
GOOGLE_MAPS_API_KEY=your_api_key_here
FILAMENT_GOOGLE_MAPS_WEB_API_KEY=your_web_key_here
FILAMENT_GOOGLE_MAPS_SERVER_API_KEY=your_server_key_here
```

### 4. Configurazione Filament
```php
// config/filament.php
'plugins' => [
    \Cheesegrits\FilamentGoogleMaps\FilamentGoogleMapsPlugin::make(),
],
```

## Alternative Consigliate

### 1. dotswan/filament-map-picker
```bash
composer require dotswan/filament-map-picker
```

### 2. webbingbrasil/filament-maps
```bash
composer require webbingbrasil/filament-maps
```

## Collegamenti Moduli
- [Modulo Geo: Google Maps Error](../laravel/Modules/Geo/docs/google-maps-service-provider-error.md)
- [Modulo Geo: Filament Integration](../laravel/Modules/Geo/docs/filament-integration.md)

## Best Practices
1. **Sempre installare pacchetti prima di usarli**
2. **Verificare compatibilità con versione Filament**
3. **Testare funzionalità dopo installazione**
4. **Documentare configurazione nei moduli**

*Ultimo aggiornamento: 2025-01-06*