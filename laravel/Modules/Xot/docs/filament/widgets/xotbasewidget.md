<<<<<<< HEAD
=======
---
title: "XotBaseWidget"
type: note
status: unverified
created: 2026-09-24
updated: 2026-10-20
module: Xot
tags: [xot, filament]
qmd: "XotBaseWidget"
---
>>>>>>> laraxot/dev
# XotBaseWidget

## Panoramica

XotBaseWidget è la classe base per tutti i widget Filament nel progetto. Fornisce funzionalità comuni e standardizza l'implementazione dei widget.

## Caratteristiche

- Estende la classe base di Filament Widget
- Fornisce metodi comuni per tutti i widget
- Gestisce la configurazione standard dei widget
- Implementa pattern di sicurezza e autorizzazioni

## Utilizzo

```php
use Modules\Xot\Filament\Widgets\XotBaseWidget;

class MyCustomWidget extends XotBaseWidget
{
    protected static string $view = 'my-module::widgets.my-custom-widget';
<<<<<<< HEAD

=======
    
>>>>>>> laraxot/dev
    protected function getData(): array
    {
        return [
            'data' => $this->getWidgetData(),
        ];
    }
}
```

## Riferimenti

<<<<<<< HEAD
- [Documentazione Filament Widgets](../Xot/docs/filament/widgets/index.md)
- [Documentazione Filament Widgets](../Xot/docs/filament/widgets/index.md)
- [Documentazione Filament Widgets](../xot/docs/filament/widgets/index.md)
- [Documentazione Filament Widgets](../xot/docs/filament/widgets/index.md)
- [XotBaseWidget](Modules/Xot/app/Filament/Widgets/XotBaseWidget.php)
=======
- [Documentazione Filament Widgets](/var/www/html/base_generic/laravel/Modules/Xot/docs/filament/widgets/index.md)
- [XotBaseWidget](/var/www/html/base_generic/laravel/Modules/Xot/app/Filament/Widgets/XotBaseWidget.php) 
>>>>>>> laraxot/dev
