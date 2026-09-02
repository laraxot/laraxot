# Ticket Comments Integration

## Panoramica

Il sistema di commenti sui ticket utilizza **Spatie Laravel Comments Livewire** per offrire funzionalità di commento in tempo reale.

## Architettura

### Componenti Coinvolti

1. **Modello Ticket** - `Modules\Fixcity\Models\Ticket`
   - Usa il trait `Spatie\Comments\Models\Concerns\HasComments`
   - Implementa `commentableName()` e `commentUrl()` per le notifiche

2. **View Livewire** - `fixcity::filament.infolist.ticket-comments`
   - Mostra commenti esistenti
   - Form per aggiungere nuovi commenti (solo autenticati)
   - Fallback per ospiti non autenticati

### Template Blade

#### `resources/views/filament/infolist/ticket-comments.blade.php`

```blade
@if ($record)
    @push('styles')
        <link rel="stylesheet" href="{{ route('laravel-comments-livewire.styles') }}">
    @endpush

    <div class="ticket-spatie-comments">
        @auth
            <livewire:comments
                :model="$record"
                hide-notification-options
                no-reactions
                :key="'ticket-spatie-comments-'.$record->getKey()"
            />
        @endauth

        @guest
            <livewire:comments
                read-only
                :model="$record"
                hide-notification-options
                no-reactions
                :key="'ticket-spatie-comments-ro-'.$record->getKey()"
            />
            <p class="mt-3 text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ route('login') }}" class="underline">
                    {{ __('comment::txt.log-in-for-comment') }}
                </a>
            </p>
        @endguest
    </div>

    @push('scripts')
        <script src="{{ route('laravel-comments-livewire.scripts') }}"></script>
    @endpush
@endif
```

#### `resources/views/components/ticket-comments.blade.php`

Versione component-based con `list-comments` e `create-comment` separati.

## Parametri Livewire

| Parametro | Tipo | Descrizione |
|-----------|------|-------------|
| `model` | Model | Il modello commentabile (Ticket) |
| `hide-notification-options` | bool | Nasconde opzioni notifica |
| `no-reactions` | bool | Disabilita reactions |
| `read-only` | bool | Solo lettura (per guest) |
| `subject` | string | Oggetto per agrupamento commenti |

## Route Livewire

Le seguenti route sono necessarie:

```php
// packages/spatie/laravel-comments-routes.php
Route::get('laravel-comments-livewire.styles', ...)
Route::get('laravel-comments-livewire.scripts', ...)
```

## Schema Infolist

Nel file `TicketInfolist.php`:

```php
public static function getCommentsSchema(): array
{
    return [
        Section::make()
            ->schema([
                ViewEntry::make('comments_list')
                    ->view('fixcity::filament.infolist.ticket-comments')
                    ->viewData(['record' => $record])
                    ->columnSpanFull(),
            ]),
    ];
}
```

## Front-Office Integration

Schema pubblico usa `getPublicFrontofficeSchema()` che include i commenti:

```php
public static function getPublicFrontofficeSchema(): array
{
    return [
        Section::make('detail')->schema(static::getFrontofficeDetailSchema()),
        Section::make('location')->schema(static::getFrontofficeLocationSchema()),
        Section::make('comments')->schema(static::getCommentsSchema()), // QUI
    ];
}
```

## Test

Per testare l'integrazione:
1. Creare un ticket con status pubblico (es: `PENDING`)
2. Accedere alla pagina del ticket in front-office
3. Verificare caricamento CSS/JS
4. Testare aggiunta commento autenticato
5. Verificare fallback guest