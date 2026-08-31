# Stato delle tabelle conservato fra una visita e l'altra

## Filtri, ordinamento, ricerche

`HasXotTable::table()` applica a ogni tabella della piattaforma:

```php
->persistFiltersInSession()
->persistSortInSession()
->persistSearchInSession()
->persistColumnSearchesInSession()
```

Chi torna a un elenco dal menu ritrova filtri, ordinamento e ricerche come li aveva
lasciati. Sono impostazioni di lettura, non dati: applicarle una volta nel trait evita che
ogni risorsa se le ricopi e che alcune restino indietro.

## Numero di pagina

Filament non ha un `persistPageInSession()`: la pagina vive solo nella query string,
quindi le voci di menu, che puntano all'URL nudo della lista, riporterebbero sempre a
pagina uno. Su elenchi da migliaia di righe significa rifare la navigazione a ogni
ritorno dall'edit di un record.

`XotBaseListRecords` colma la lacuna con tre hook Livewire, quindi il comportamento vale
per tutti gli elenchi della piattaforma:

- `mount()` alza `$shouldRestoreListPage` solo se la richiesta non porta `page` in query
  string, cosi' un link esplicito o un preferito vincono sempre sulla sessione;
- `rendering()` legge la pagina dalla sessione e chiama `setPage()`;
- `dehydrate()` salva la pagina corrente alla fine di ogni richiesta.

Chiave di sessione: `xot.list_page.{classe concreta della pagina}`, distinta per elenco.

## Perche' il ripristino sta in rendering e non in mount

`setPage()` interroga la tabella per il nome della pagina, e la tabella viene costruita
dall'hook `bootedInteractsWithTable`, che Livewire esegue dopo `mount()` e dopo `booted()`.
Chiamare `setPage()` prima solleva:

```
Typed property Filament\Resources\Pages\ListRecords::$table must not be accessed before initialization
```

`rendering()` gira a tabella pronta e prima che la vista legga le righe.

Il flag alzato in `mount()` e' necessario perche' `rendering()` viene eseguito a ogni
richiesta Livewire: senza flag, ogni click sulla paginazione verrebbe annullato dal
ripristino.

## Verifica

```bash
php artisan tinker --execute='
use Filament\Facades\Filament;
use Livewire\Livewire;
$cls = \Modules\Progressioni\Filament\Resources\SchedaResource\Pages\ListSchedas::class;
auth()->login(\Modules\Xot\Datas\XotData::make()->getUserClass()::query()->first());
Filament::setCurrentPanel(Filament::getPanel("progressioni::admin"));
session()->put("xot.list_page.".$cls, 4);
echo Livewire::test($cls)->instance()->getPage();
'
```

Deve stampare `4`.

## Effetti collaterali da conoscere

Lo stato e' per utente e per elenco. Una segnalazione del tipo "vedo un elenco filtrato che
non ho filtrato io" e' quasi sempre stato di sessione di una visita precedente: si azzera
dal pannello con il reset dei filtri, non svuotando la sessione.

## Riferimenti

- `Modules/Xot/app/Filament/Traits/HasXotTable.php`
- `Modules/Xot/app/Filament/Resources/Pages/XotBaseListRecords.php`
