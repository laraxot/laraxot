# File duplicati per sola differenza di maiuscole

## Il sintomo

`./vendor/bin/pest` (suite completa) non partiva affatto:

```
Pest\Exceptions\FatalException
Cannot redeclare function Modules\Lang\Tests\Unit\Actions\makeGetAllTranslationAction()
(previously declared in Modules/Lang/tests/Unit/Actions/GetAllTranslationActionTest.php:13)
at Modules/Lang/tests/unit/actions/GetAllTranslationActionTest.php:13
```

Il fatal avviene **in fase di caricamento**, prima che un solo test giri: un modulo
qualsiasi con questo difetto rende non eseguibile l'intera suite. Eseguendo un modulo
alla volta il problema non si vede, perché il conflitto nasce dal caricamento
simultaneo.

## La causa

Lo stesso file esiste due volte con path che differiscono solo per maiuscole:

- `Modules/Lang/tests/Unit/Actions/GetAllTranslationActionTest.php`
- `Modules/Lang/tests/unit/actions/GetAllTranslationActionTest.php`

Su filesystem **case-insensitive** (macOS, Windows) sono lo stesso file e il difetto
resta invisibile; su Linux sono due file distinti, entrambi caricati da PHPUnit, e la
funzione dichiarata nel file viene registrata due volte.

Origine tipica: checkout o copia da una macchina case-insensitive, poi committato.

## Censimento 2026-08-31

18 gruppi di file test che differivano solo per maiuscole:

- **9 identici** — copia pura, rimossa quella col path non canonico;
- **9 divergenti** — varianti reali dello stesso test, che convivono senza rompere il
  caricamento (Pest genera classi anonime, non funzioni con nome). Lasciati: scegliere
  quale versione tenere e' una decisione sul contenuto dei test, non di igiene del
  repo, e va presa da chi li ha scritti.

Rimosse anche tre coppie di **cartelle** duplicate (`tests/unit` + `tests/Unit`,
`tests/feature` + `tests/Feature` in Lang e Media, `tests/filament` in Media) e un file
con nome interamente minuscolo (`mediaconvertresourcetest.php`).

## Come accorgersene

```bash
cd laravel
find Modules/*/tests -name "*.php" \
  | awk '{ print tolower($0) "\t" $0 }' | sort \
  | awk -F'\t' '{ if ($1 == p1 && $2 != p2) print p2 " <-> " $2; p1 = $1; p2 = $2 }'
```

Zero righe = pulito. Vale la pena eseguirlo prima di dichiarare verde una suite
completa, e dopo ogni porting che copia file da un'altra macchina.

## Regola

Nomi di cartelle e file dei test seguono PSR-4: `Feature/`, `Unit/`, `Fixtures/`,
`Filament/Resources/`, e `NomeDelTest.php` in PascalCase. Un path che differisce solo
per maiuscole non e' una variante: e' un file che non doveva essere committato.
