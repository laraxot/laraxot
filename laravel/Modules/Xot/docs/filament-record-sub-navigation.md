# Sub navigation delle pagine di record

Regola valida per ogni risorsa che estende `XotBaseResource`.

## Come Filament costruisce la sub navigation

Una pagina di risorsa che usa `Filament\Resources\Pages\Concerns\InteractsWithRecord`
espone `getSubNavigation()`, che delega a `Resource::getRecordSubNavigation($page)`.
La risorsa converte un elenco di classi pagina in voci di menu con
`$page->generateNavigationItems([...])`.

`XotBaseResource` imposta gia' la posizione:

```php
protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;
```

Le voci compaiono quindi come tab sopra il contenuto della pagina.

## Costruire l'elenco dalle pagine registrate, non a mano

Nel monorepo una risorsa base sta in un modulo piattaforma (per esempio `Ptv`) e i moduli
foglia la estendono sovrascrivendo `getPages()` con le proprie classi. Se la base elencasse
le classi a mano, le tab del modulo foglia punterebbero alle pagine della piattaforma e
`generateNavigationItems()` non troverebbe la rotta corrispondente.

L'elenco va quindi derivato da `static::getPages()`, che in una classe figlia restituisce
gia' le classi giuste:

```php
public static function getRecordSubNavigation(Page $page): array
{
    $pages = static::getPages();

    $components = [];
    foreach (['view', 'edit', 'compila', 'assenze'] as $key) {
        $registration = $pages[$key] ?? null;
        if (! $registration instanceof PageRegistration) {
            continue;
        }

        $pageClass = $registration->getPage();
        if (! is_subclass_of($pageClass, Page::class)) {
            continue;
        }

        $components[] = $pageClass;
    }

    return $page->generateNavigationItems($components);
}
```

Il controllo `is_subclass_of()` non e' decorativo: `PageRegistration::getPage()` e' tipizzato
`string` e senza quel controllo PHPStan segnala `argument.type` sulla chiamata a
`generateNavigationItems()`.

L'ordine delle chiavi nell'array e' l'ordine delle tab. Una chiave assente viene saltata,
quindi la stessa base funziona anche per risorse che non registrano tutte le pagine.

## Voci che non compaiono

`generateNavigationItems()` salta la pagina quando `shouldRegisterNavigation()` o
`canAccess()` restituiscono `false`. Le pagine standard (`edit`, `view`) passano dalle
policy della risorsa, quindi in console senza utente autenticato la voce sparisce anche
se la registrazione e' corretta: verificare sempre da browser autenticato.

## Etichetta e icona della voce

La voce usa `getNavigationLabel()` e `getNavigationIcon()` della pagina. Le pagine che
compongono `NavigationLabelTrait` leggono le chiavi di lingua del modulo: se
`navigation.label` manca nel file di lingua, la tab mostra la chiave grezza
(per esempio `progressioni::compila_scheda.navigation.label`). Le pagine che non compongono
il trait usano le proprieta' statiche `$navigationLabel` e `$navigationIcon`.

## Nascondere la sub navigation su una pagina

Un flusso a pagina piena con salvataggio e ritorno propri non deve mostrare le tab.
Si sovrascrive `getSubNavigation()` sulla pagina, non si tolgono voci dalla risorsa:

```php
public function getSubNavigation(): array
{
    return [];
}
```

La voce resta cosi' raggiungibile dalle altre pagine, ma la pagina di destinazione
si presenta senza tab.

## Pagine di record che elencano una relazione

Per una tab che elenca record collegati si estende
`Modules\Xot\Filament\Resources\XotBaseResource\Pages\XotBaseManageRelatedRecords`
indicando `protected static string $relationship`. La tabella arriva da `HasXotTable`,
che compone `HasTableLayoutPage` per la proprieta' `$layoutView`: non serve dichiararla
nella pagina.

Attenzione a due default della base:

- `getTableRecordTitleAttribute()` restituisce `name`. Se il modello correlato non ha
  quella colonna va sovrascritto, altrimenti ricerca e titoli dei record puntano a un
  attributo inesistente.
- `getTableActions()` genera azioni `view` ed `edit` verso la risorsa della pagina.
  Per una tab di sola lettura vanno azzerati `getTableActions()`,
  `getTableHeaderActions()` e `getTableBulkActions()`.

## Riferimenti

- Documentazione Filament: `resources/editing-records`, sezione sulle edit page in sub navigation
- `Modules/Xot/app/Filament/Resources/XotBaseResource.php`
- `Modules/Xot/app/Filament/Resources/XotBaseResource/Pages/XotBaseManageRelatedRecords.php`
- `Modules/Ptv/docs/scheda-record-sub-navigation.md`
