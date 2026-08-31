---
title: "widget — Consolidated Documentation"
module: xot
type: integration
tags: [integrations, modules, xot]
created: 2026-08-24
updated: 2026-08-24
---

# widget — Consolidated Documentation

Consolidated from **12** individual files.

## Table of Contents

- [Widget FileUpload Errors - Troubleshooting Guide](#widget-fileuploads)
- [Widget Implementation Rules - Xot Module](#widget-implementation-rules-2-widget-implementation-rules-xot-module)
- [Widget Implementation Rules - Xot Module](#widget-implementation-rules-conflict)
- [Widget Implementation Rules - Xot Module](#widget-implementation-rules-widget-implementation-rules-xot-module)
- [Widget Implementation Rules - Xot Module](#widget-implementation-rules-xot-module)
- [Widget Implementation Rules - Xot Module](#widget-implementation-rules)
- [Widget Implementation Rules - Xot Module](#widget-implementation-rules_2)
- [Widget Implementation Rules - Xot Module](#widget-implementation-widget-implementation-rules-xot-module)
- [Widget Implementation Rules - Xot Module](#widget-implementation)
- [Widget View Resolution - Risoluzione Automatica vs Manuale](#widget-view-resolution)
- [Inizializzazione dei Widget XotBaseWidget](#widgets-initialization)
- [---](#widgetsization)

---

## widget-fileuploads

*Consolidated from: `widget-fileuploads.md`*


## Errore: "foreach() argument must be of type array|object, string given"

### Sintomi
- Errore durante il caricamento di pagine con widget che contengono FileUpload
- Stack trace che punta a `Filament\Forms\Components\BaseFileUpload::getUploadedFiles`
- Si verifica tipicamente quando si caricano dati esistenti dal database

### Causa Radice
Filament si aspetta che i componenti `FileUpload` ricevano array di file, ma quando i dati vengono caricati dal database tramite `model->toArray()`, i campi file upload possono essere stringhe (percorsi file).

### Scenario Tipico
1. Widget carica dati esistenti con `$model->toArray()`
2. Campi file upload nel database sono stringhe: `"file.pdf"`
3. Filament riceve stringhe invece di array: `["file.pdf"]`
4. Errore durante l'iterazione con `foreach()`

## Soluzioni

### Soluzione A: Correzione nel Widget (Raccomandato)

Nel metodo che popola i dati del form (es. `getFormFill()`, `mount()`):

```php
public function getFormFill(): array
{
    $model = $this->getFormModel();

    if ($model->exists) {
        $data = $model->toArray();

        // Converti campi file upload da stringhe ad array
        $attachments = $model::$attachments ?? [];
        foreach ($attachments as $attachment) {
            if (isset($data[$attachment]) && is_string($data[$attachment])) {
                $data[$attachment] = [$data[$attachment]];
            }
        }

        return $data;
    }

    return [];
}
```

### Soluzione B: Correzione nel Resource Schema

Nel metodo che definisce lo schema degli allegati:

```php
Forms\Components\FileUpload::make($attachment)
    ->formatStateUsing(function ($state, $set) use ($attachment) {
        if (is_string($state)) {
            $sessionFiles = [$state];
        } elseif (is_array($state)) {
            $sessionFiles = $state;
        } else {
            $sessionFiles = [];
        }

        $set($attachment, $sessionFiles);
        return $sessionFiles;
    })
```

### Soluzione C: Uso di Accessors nel Model

Definire accessors nel modello per gestire automaticamente la conversione:

```php
// Nel modello
public function getHealthCardAttribute($value)
{
    if (is_string($value)) {
        return [$value];
    }
    return $value;
}

protected function casts(): array
{
    return [
        'health_card' => 'array',
        'identity_document' => 'array',
        // Altri campi file...
    ];
}
```

## Pattern di Prevenzione

### 1. Controllo Tipo Dinamico

```php
private function ensureFileFieldsAreArrays(array $data, array $fileFields): array
{
    foreach ($fileFields as $field) {
        if (isset($data[$field]) && is_string($data[$field])) {
            $data[$field] = [$data[$field]];
        }
    }
    return $data;
}
```

### 2. Trait per Widget con FileUpload

```php
trait HandlesFileUploadFields
{
    protected function normalizeFileUploadFields(array $data, ?array $fileFields = null): array
    {
        $fileFields = $fileFields ?? $this->getFileUploadFields();

        foreach ($fileFields as $field) {
            if (isset($data[$field]) && is_string($data[$field])) {
                $data[$field] = [$data[$field]];
            }
        }

        return $data;
    }

    protected function getFileUploadFields(): array
    {
        $model = $this->getFormModel();
        return property_exists($model, 'attachments') ? $model::$attachments : [];
    }
}
```

### 3. Helper per Modelli

```php
// In BaseModel o trait condiviso
public function getFileUploadFieldsAsArrays(array $fields = null): array
{
    $fields = $fields ?? static::$attachments ?? [];
    $data = $this->toArray();

    foreach ($fields as $field) {
        if (isset($data[$field]) && is_string($data[$field])) {
            $data[$field] = [$data[$field]];
        }
    }

    return $data;
}
```

## Debug e Diagnostica

### Controllo Rapido

```php
// Aggiungi questo al widget per debug
public function mount()
{
    $data = $this->getFormFill();

    foreach (['health_card', 'identity_document'] as $field) {
        if (isset($data[$field])) {
            Log::info("Field {$field} type: " . gettype($data[$field]));
            Log::info("Field {$field} value: " . json_encode($data[$field]));
        }
    }

    $this->form->fill($data);
}
```

### Verifica Database

```sql
-- Controlla come sono salvati i campi nel database
SELECT health_card, identity_document, isee_certificate
FROM users
WHERE id = 'specific-user-id';
```

### Verifica Modello

```php
// Nel tinker
$user = User::find('user-id');
var_dump($user->health_card); // Dovrebbe essere string o null
var_dump($user->toArray()['health_card']); // Controlla il tipo dopo toArray()
```

## Test di Regressione

### Test Unitario

```php
public function test_file_upload_fields_are_converted_to_arrays()
{
    $user = User::factory()->create([
        'health_card' => 'session-uploads/test.pdf',
        'identity_document' => 'session-uploads/doc.pdf',
    ]);

    $widget = new RegistrationWidget();
    $widget->type = 'patient';
    // Setup del widget...

    $data = $widget->getFormFill();

    $this->assertIsArray($data['health_card']);
    $this->assertIsArray($data['identity_document']);
    $this->assertEquals(['session-uploads/test.pdf'], $data['health_card']);
}
```

### Test di Integrazione

```php
public function test_registration_widget_loads_without_errors_for_existing_user()
{
    $user = User::factory()->create([
        'health_card' => 'session-uploads/test.pdf',
    ]);

    $response = $this->get("/auth/patient/register?email={$user->email}&token={$user->remember_token}");

    $response->assertStatus(200);
    // Non dovrebbe esserci errore foreach()
}
```

## Riferimenti

- [Filament FileUpload Documentation](https://filamentphp.com/project_docs/forms/fields/file-upload)
- [Laravel Eloquent Accessors](https://laravel.com/project_docs/eloquent-accessors)
- [Livewire File Uploads](https://livewire.laravel.com/project_docs/file-uploads)

## Casi Correlati

Questo pattern si applica anche a:
- Upload multipli che diventano stringhe JSON
- Campi che memorizzano array ma vengono serializzati come stringhe
- Widget che caricano dati da relazioni con upload file
- Form che ripopolano campi da sessioni interrotte

---

**Tipo**: Troubleshooting Guide
**Modulo**: Xot (Base)
**Applicabilità**: Tutti i widget con FileUpload che caricano dati esistenti
**Aggiornato**: [DATE]

---

## widget-implementation-rules-2-widget-implementation-rules-xot-module

*Consolidated from: `widget-implementation-rules-2-widget-implementation-rules-xot-module.md`*


## 🎯 Regole Fondamentali per Widget

### 1. **Tipi di Widget Disponibili**

#### **XotBaseWidget** - Per widget con form
```php
// ✅ CORRETTO - Widget con form
class MyFormWidget extends XotBaseWidget
{
    public function getFormSchema(): array
    {
        return [
            // Form components
        ];
    }
}
```

#### **XotBaseTableWidget** - Per widget di tabella
```php
// ✅ CORRETTO - Widget di tabella
class MyTableWidget extends XotBaseTableWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns($this->getTableColumns());
    }
}
```

### 2. **MAI mescolare i tipi**
```php
// ❌ SBAGLIATO - XotBaseWidget per tabella
class MyTableWidget extends XotBaseWidget
{
    public function table(Table $table): Table
    {
        // XotBaseWidget richiede getFormSchema()!
    }
}

// ✅ CORRETTO - XotBaseTableWidget per tabella
class MyTableWidget extends XotBaseTableWidget
{
    public function table(Table $table): Table
    {
        // XotBaseTableWidget è progettato per tabelle
    }
}
```

### 3. **Metodi Obbligatori per Tipo**

#### **XotBaseWidget**
- `getFormSchema(): array` - **OBBLIGATORIO**

#### **XotBaseTableWidget**
- `table(Table $table): Table` - **OBBLIGATORIO**
- `getTableQuery()` - **OBBLIGATORIO**
- `getTableColumns(): array` - **OBBLIGATORIO**

## 🏗️ Implementazione Corretta

### 1. **Widget con Filtri (XotBaseWidget)**
```php
<?php

declare(strict_types=1);

namespace Modules\SurveyModule\Filament\Widgets;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Modules\Xot\Filament\Widgets\XotBaseWidget;

class QuestionChartFilterWidget extends XotBaseWidget
{
    protected static ?string $heading = 'Filter Options';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 1;

    public ?string $dateFrom = null;
    public ?string $dateTo = null;
    public ?string $answerFilter = null;

    public function getFormSchema(): array
    {
        return [
            DatePicker::make('dateFrom')
                ->live()
                ->afterStateUpdated(fn () => $this->updateFilters()),
            
            DatePicker::make('dateTo')
                ->live()
                ->afterStateUpdated(fn () => $this->updateFilters()),
            
            Select::make('answerFilter')
                ->options([
                    'all' => 'All Answers',
                    'answered' => 'Answered Only',
                    'not_answered' => 'Not Answered',
                ])
                ->default('all')
                ->live()
                ->afterStateUpdated(fn () => $this->updateFilters()),
        ];
    }

    public function updateFilters(): void
    {
        $this->dispatch('filters-updated', [
            'dateFrom' => $this->dateFrom,
            'dateTo' => $this->dateTo,
            'answerFilter' => $this->answerFilter,
        ]);
    }
}
```

### 2. **Widget di Tabella (XotBaseTableWidget)**
```php
<?php

declare(strict_types=1);

namespace Modules\SurveyModule\Filament\Widgets;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Xot\Filament\Widgets\XotBaseTableWidget;

class QuestionChartDataWidget extends XotBaseTableWidget
{
    protected static ?string $heading = 'Question Answer Data';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('submitdate')
                    ->dateTime()
                    ->sortable()
                    ->searchable(),
                
                TextColumn::make('answert')
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 50 ? $state : null;
                    })
                    ->searchable(),
                
                TextColumn::make('answer_lang')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'it' => 'success',
                        'en' => 'info',
                        'fr' => 'warning',
                        'de' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
            ])
            ->defaultSort('submitdate', 'desc')
            ->paginated([10, 25, 50, 100])
            ->poll('30s');
    }

    protected function getTableQuery()
    {
        $record = $this->getRecord();
        
        if (!$record) {
            return $record->answers()->whereRaw('1 = 0');
        }
        
        return $record->answers()
            ->select(['submitdate', 'answert', 'answer_lang'])
            ->when($record->date_from, function ($query, $dateFrom) {
                $query->where('submitdate', '>=', $dateFrom);
            })
            ->when($record->date_to, function ($query, $dateTo) {
                $query->where('submitdate', '<=', $dateTo);
            });
    }

    protected function getRecord()
    {
        return $this->getTableRecord();
    }
}
```

### 3. **Widget di Grafico (XotBaseWidget)**
```php
<?php

declare(strict_types=1);

namespace Modules\SurveyModule\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Modules\Xot\Filament\Widgets\XotBaseWidget;

class QuestionChartWidget extends XotBaseWidget
{
    protected static ?string $heading = 'Question Chart Visualization';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 3;

    public function getFormSchema(): array
    {
        return []; // No form needed for chart widget
    }

    protected function getData(): array
    {
        $record = $this->getRecord();
        
        if (!$record) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }
        
        $chartData = $this->getChartData($record);
        
        return [
            'datasets' => [
                [
                    'label' => 'Answer Distribution',
                    'data' => $chartData['values'],
                    'backgroundColor' => $this->getChartColors(count($chartData['values'])),
                    'borderColor' => $this->getChartColors(count($chartData['values']), 0.8),
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $chartData['labels'],
        ];
    }

    protected function getType(): string
    {
        $record = $this->getRecord();
        
        if (!$record) {
            return 'bar';
        }
        
        return match ($record->chart_type) {
            'pie' => 'pie',
            'doughnut' => 'doughnut',
            'line' => 'line',
            default => 'bar',
        };
    }

    protected function getChartData($record): array
    {
        $answers = $record->answers()
            ->select(['answert', 'answer_lang'])
            ->whereNotNull('answert')
            ->when($record->date_from, function ($query, $dateFrom) {
                $query->where('submitdate', '>=', $dateFrom);
            })
            ->when($record->date_to, function ($query, $dateTo) {
                $query->where('submitdate', '<=', $dateTo);
            })
            ->get();
        
        $grouped = $answers->groupBy('answer_lang');
        
        $labels = [];
        $values = [];
        
        foreach ($grouped as $lang => $langAnswers) {
            $labels[] = $lang ?: 'Unknown';
            $values[] = $langAnswers->count();
        }
        
        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }

    protected function getChartColors(int $count, float $alpha = 1.0): array
    {
        $baseColors = [
            'rgba(54, 162, 235, ' . $alpha . ')',
            'rgba(255, 99, 132, ' . $alpha . ')',
            'rgba(255, 205, 86, ' . $alpha . ')',
            'rgba(75, 192, 192, ' . $alpha . ')',
            'rgba(153, 102, 255, ' . $alpha . ')',
            'rgba(255, 159, 64, ' . $alpha . ')',
            'rgba(199, 199, 199, ' . $alpha . ')',
            'rgba(83, 102, 255, ' . $alpha . ')',
        ];
        
        $colors = [];
        for ($i = 0; $i < $count; $i++) {
            $colors[] = $baseColors[$i % count($baseColors)];
        }
        
        return $colors;
    }
}
```

## 🚫 Errori Comuni da Evitare

### 1. **Usare XotBaseWidget per Tabelle**
```php
// ❌ SBAGLIATO
class MyTableWidget extends XotBaseWidget
{
    public function table(Table $table): Table
    {
        // XotBaseWidget richiede getFormSchema()!
    }
}

// ✅ CORRETTO
class MyTableWidget extends XotBaseTableWidget
{
    public function table(Table $table): Table
    {
        // XotBaseTableWidget è per tabelle
    }
}
```

### 2. **Non Implementare Metodi Obbligatori**
```php
// ❌ SBAGLIATO - Manca getFormSchema()
class MyWidget extends XotBaseWidget
{
    // Errore: deve implementare getFormSchema()
}

// ✅ CORRETTO
class MyWidget extends XotBaseWidget
{
    public function getFormSchema(): array
    {
        return [];
    }
}
```

### 3. **Mescolare Responsabilità**
```php
// ❌ SBAGLIATO - Widget che fa tutto
class MyWidget extends XotBaseWidget
{
    public function getFormSchema(): array
    {
        return []; // Form vuoto
    }
    
    public function table(Table $table): Table
    {
        // Tabella in widget di form
    }
}

// ✅ CORRETTO - Separare responsabilità
class MyFilterWidget extends XotBaseWidget
{
    public function getFormSchema(): array
    {
        return [/* form components */];
    }
}

class MyTableWidget extends XotBaseTableWidget
{
    public function table(Table $table): Table
    {
        return $table->columns(/* table columns */);
    }
}
```

## 📋 Checklist Implementazione

### ✅ Prima di Implementare
- [ ] Decidere se widget ha form o tabella
- [ ] Scegliere XotBaseWidget o XotBaseTableWidget
- [ ] Verificare metodi obbligatori da implementare

### ✅ Durante Implementazione
- [ ] Implementare tutti i metodi obbligatori
- [ ] Seguire convenzioni naming
- [ ] Usare type hints corretti
- [ ] Documentare PHPDoc

### ✅ Dopo Implementazione
- [ ] Verificare PHPStan livello 10
- [ ] Controllare conformità PSR-12
- [ ] Testare funzionalità
- [ ] Aggiornare documentazione

## 📚 Riferimenti

- [XotBaseWidget Implementation](./xotbasewidget_implementation.md)
- [XotBaseTableWidget Implementation](./xotbasetablewidget_implementation.md)
- [Laraxot Architecture Rules](./LARAXOT_ARCHITECTURE_RULES.md)

Queste regole garantiscono implementazione corretta dei widget seguendo l'architettura Laraxot.



---

## widget-implementation-rules-conflict

*Consolidated from: `widget-implementation-rules-conflict.md`*


## 🎯 Regole Fondamentali per Widget

### 1. **Tipi di Widget Disponibili**

#### **XotBaseWidget** - Per widget con form
```php
// ✅ CORRETTO - Widget con form
class MyFormWidget extends XotBaseWidget
{
    public function getFormSchema(): array
    {
        return [
            // Form components
        ];
    }
}
```

#### **XotBaseTableWidget** - Per widget di tabella
```php
// ✅ CORRETTO - Widget di tabella
class MyTableWidget extends XotBaseTableWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns($this->getTableColumns());
    }
}
```

### 2. **MAI mescolare i tipi**
```php
// ❌ SBAGLIATO - XotBaseWidget per tabella
class MyTableWidget extends XotBaseWidget
{
    public function table(Table $table): Table
    {
        // XotBaseWidget richiede getFormSchema()!
    }
}

// ✅ CORRETTO - XotBaseTableWidget per tabella
class MyTableWidget extends XotBaseTableWidget
{
    public function table(Table $table): Table
    {
        // XotBaseTableWidget è progettato per tabelle
    }
}
```

### 3. **Metodi Obbligatori per Tipo**

#### **XotBaseWidget**
- `getFormSchema(): array` - **OBBLIGATORIO**

#### **XotBaseTableWidget**
- `table(Table $table): Table` - **OBBLIGATORIO**
- `getTableQuery()` - **OBBLIGATORIO**
- `getTableColumns(): array` - **OBBLIGATORIO**

## 🏗️ Implementazione Corretta

### 1. **Widget con Filtri (XotBaseWidget)**
```php
<?php

declare(strict_types=1);

namespace Modules\SurveyModule\Filament\Widgets;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Modules\Xot\Filament\Widgets\XotBaseWidget;

class QuestionChartFilterWidget extends XotBaseWidget
{
    protected static ?string $heading = 'Filter Options';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 1;

    public ?string $dateFrom = null;
    public ?string $dateTo = null;
    public ?string $answerFilter = null;

    public function getFormSchema(): array
    {
        return [
            DatePicker::make('dateFrom')
                ->live()
                ->afterStateUpdated(fn () => $this->updateFilters()),

            DatePicker::make('dateTo')
                ->live()
                ->afterStateUpdated(fn () => $this->updateFilters()),

            Select::make('answerFilter')
                ->options([
                    'all' => 'All Answers',
                    'answered' => 'Answered Only',
                    'not_answered' => 'Not Answered',
                ])
                ->default('all')
                ->live()
                ->afterStateUpdated(fn () => $this->updateFilters()),
        ];
    }

    public function updateFilters(): void
    {
        $this->dispatch('filters-updated', [
            'dateFrom' => $this->dateFrom,
            'dateTo' => $this->dateTo,
            'answerFilter' => $this->answerFilter,
        ]);
    }
}
```

### 2. **Widget di Tabella (XotBaseTableWidget)**
```php
<?php

declare(strict_types=1);

namespace Modules\SurveyModule\Filament\Widgets;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Xot\Filament\Widgets\XotBaseTableWidget;

class QuestionChartDataWidget extends XotBaseTableWidget
{
    protected static ?string $heading = 'Question Answer Data';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('submitdate')
                    ->dateTime()
                    ->sortable()
                    ->searchable(),

                TextColumn::make('answert')
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 50 ? $state : null;
                    })
                    ->searchable(),

                TextColumn::make('answer_lang')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'it' => 'success',
                        'en' => 'info',
                        'fr' => 'warning',
                        'de' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
            ])
            ->defaultSort('submitdate', 'desc')
            ->paginated([10, 25, 50, 100])
            ->poll('30s');
    }

    protected function getTableQuery()
    {
        $record = $this->getRecord();

        if (!$record) {
            return $record->answers()->whereRaw('1 = 0');
        }

        return $record->answers()
            ->select(['submitdate', 'answert', 'answer_lang'])
            ->when($record->date_from, function ($query, $dateFrom) {
                $query->where('submitdate', '>=', $dateFrom);
            })
            ->when($record->date_to, function ($query, $dateTo) {
                $query->where('submitdate', '<=', $dateTo);
            });
    }

    protected function getRecord()
    {
        return $this->getTableRecord();
    }
}
```

### 3. **Widget di Grafico (XotBaseWidget)**
```php
<?php

declare(strict_types=1);

namespace Modules\SurveyModule\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Modules\Xot\Filament\Widgets\XotBaseWidget;

class QuestionChartWidget extends XotBaseWidget
{
    protected static ?string $heading = 'Question Chart Visualization';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 3;

    public function getFormSchema(): array
    {
        return []; // No form needed for chart widget
    }

    protected function getData(): array
    {
        $record = $this->getRecord();

        if (!$record) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        $chartData = $this->getChartData($record);

        return [
            'datasets' => [
                [
                    'label' => 'Answer Distribution',
                    'data' => $chartData['values'],
                    'backgroundColor' => $this->getChartColors(count($chartData['values'])),
                    'borderColor' => $this->getChartColors(count($chartData['values']), 0.8),
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $chartData['labels'],
        ];
    }

    protected function getType(): string
    {
        $record = $this->getRecord();

        if (!$record) {
            return 'bar';
        }

        return match ($record->chart_type) {
            'pie' => 'pie',
            'doughnut' => 'doughnut',
            'line' => 'line',
            default => 'bar',
        };
    }

    protected function getChartData($record): array
    {
        $answers = $record->answers()
            ->select(['answert', 'answer_lang'])
            ->whereNotNull('answert')
            ->when($record->date_from, function ($query, $dateFrom) {
                $query->where('submitdate', '>=', $dateFrom);
            })
            ->when($record->date_to, function ($query, $dateTo) {
                $query->where('submitdate', '<=', $dateTo);
            })
            ->get();

        $grouped = $answers->groupBy('answer_lang');

        $labels = [];
        $values = [];

        foreach ($grouped as $lang => $langAnswers) {
            $labels[] = $lang ?: 'Unknown';
            $values[] = $langAnswers->count();
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }

    protected function getChartColors(int $count, float $alpha = 1.0): array
    {
        $baseColors = [
            'rgba(54, 162, 235, ' . $alpha . ')',
            'rgba(255, 99, 132, ' . $alpha . ')',
            'rgba(255, 205, 86, ' . $alpha . ')',
            'rgba(75, 192, 192, ' . $alpha . ')',
            'rgba(153, 102, 255, ' . $alpha . ')',
            'rgba(255, 159, 64, ' . $alpha . ')',
            'rgba(199, 199, 199, ' . $alpha . ')',
            'rgba(83, 102, 255, ' . $alpha . ')',
        ];

        $colors = [];
        for ($i = 0; $i < $count; $i++) {
            $colors[] = $baseColors[$i % count($baseColors)];
        }

        return $colors;
    }
}
```

## 🚫 Errori Comuni da Evitare

### 1. **Usare XotBaseWidget per Tabelle**
```php
// ❌ SBAGLIATO
class MyTableWidget extends XotBaseWidget
{
    public function table(Table $table): Table
    {
        // XotBaseWidget richiede getFormSchema()!
    }
}

// ✅ CORRETTO
class MyTableWidget extends XotBaseTableWidget
{
    public function table(Table $table): Table
    {
        // XotBaseTableWidget è per tabelle
    }
}
```

### 2. **Non Implementare Metodi Obbligatori**
```php
// ❌ SBAGLIATO - Manca getFormSchema()
class MyWidget extends XotBaseWidget
{
    // Errore: deve implementare getFormSchema()
}

// ✅ CORRETTO
class MyWidget extends XotBaseWidget
{
    public function getFormSchema(): array
    {
        return [];
    }
}
```

### 3. **Mescolare Responsabilità**
```php
// ❌ SBAGLIATO - Widget che fa tutto
class MyWidget extends XotBaseWidget
{
    public function getFormSchema(): array
    {
        return []; // Form vuoto
    }

    public function table(Table $table): Table
    {
        // Tabella in widget di form
    }
}

// ✅ CORRETTO - Separare responsabilità
class MyFilterWidget extends XotBaseWidget
{
    public function getFormSchema(): array
    {
        return [/* form components */];
    }
}

class MyTableWidget extends XotBaseTableWidget
{
    public function table(Table $table): Table
    {
        return $table->columns(/* table columns */);
    }
}
```

## 📋 Checklist Implementazione

### ✅ Prima di Implementare
- [ ] Decidere se widget ha form o tabella
- [ ] Scegliere XotBaseWidget o XotBaseTableWidget
- [ ] Verificare metodi obbligatori da implementare

### ✅ Durante Implementazione
- [ ] Implementare tutti i metodi obbligatori
- [ ] Seguire convenzioni naming
- [ ] Usare type hints corretti
- [ ] Documentare PHPDoc

### ✅ Dopo Implementazione
- [ ] Verificare PHPStan livello 10
- [ ] Controllare conformità PSR-12
- [ ] Testare funzionalità
- [ ] Aggiornare documentazione

## 📚 Riferimenti

- [XotBaseWidget Implementation](./xotbasewidget_implementation.md)
- [XotBaseTableWidget Implementation](./xotbasetablewidget_implementation.md)
- [Laraxot Architecture Rules](./LARAXOT_ARCHITECTURE_RULES.md)

Queste regole garantiscono implementazione corretta dei widget seguendo l'architettura Laraxot.

---

## widget-implementation-rules-widget-implementation-rules-xot-module

*Consolidated from: `widget-implementation-rules-widget-implementation-rules-xot-module.md`*


## 🎯 Regole Fondamentali per Widget

### 1. **Tipi di Widget Disponibili**

#### **XotBaseWidget** - Per widget con form
```php
// ✅ CORRETTO - Widget con form
class MyFormWidget extends XotBaseWidget
{
    public function getFormSchema(): array
    {
        return [
            // Form components
        ];
    }
}
```

#### **XotBaseTableWidget** - Per widget di tabella
```php
// ✅ CORRETTO - Widget di tabella
class MyTableWidget extends XotBaseTableWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns($this->getTableColumns());
    }
}
```

### 2. **MAI mescolare i tipi**
```php
// ❌ SBAGLIATO - XotBaseWidget per tabella
class MyTableWidget extends XotBaseWidget
{
    public function table(Table $table): Table
    {
        // XotBaseWidget richiede getFormSchema()!
    }
}

// ✅ CORRETTO - XotBaseTableWidget per tabella
class MyTableWidget extends XotBaseTableWidget
{
    public function table(Table $table): Table
    {
        // XotBaseTableWidget è progettato per tabelle
    }
}
```

### 3. **Metodi Obbligatori per Tipo**

#### **XotBaseWidget**
- `getFormSchema(): array` - **OBBLIGATORIO**

#### **XotBaseTableWidget**
- `table(Table $table): Table` - **OBBLIGATORIO**
- `getTableQuery()` - **OBBLIGATORIO**
- `getTableColumns(): array` - **OBBLIGATORIO**

## 🏗️ Implementazione Corretta

### 1. **Widget con Filtri (XotBaseWidget)**
```php
<?php

declare(strict_types=1);

namespace Modules\<nome progetto>\Filament\Widgets;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Modules\Xot\Filament\Widgets\XotBaseWidget;

class QuestionChartFilterWidget extends XotBaseWidget
{
    protected static ?string $heading = 'Filter Options';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 1;

    public ?string $dateFrom = null;
    public ?string $dateTo = null;
    public ?string $answerFilter = null;

    public function getFormSchema(): array
    {
        return [
            DatePicker::make('dateFrom')
                ->live()
                ->afterStateUpdated(fn () => $this->updateFilters()),

            DatePicker::make('dateTo')
                ->live()
                ->afterStateUpdated(fn () => $this->updateFilters()),

            Select::make('answerFilter')
                ->options([
                    'all' => 'All Answers',
                    'answered' => 'Answered Only',
                    'not_answered' => 'Not Answered',
                ])
                ->default('all')
                ->live()
                ->afterStateUpdated(fn () => $this->updateFilters()),
        ];
    }

    public function updateFilters(): void
    {
        $this->dispatch('filters-updated', [
            'dateFrom' => $this->dateFrom,
            'dateTo' => $this->dateTo,
            'answerFilter' => $this->answerFilter,
        ]);
    }
}
```

### 2. **Widget di Tabella (XotBaseTableWidget)**
```php
<?php

declare(strict_types=1);

namespace Modules\<nome progetto>\Filament\Widgets;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Xot\Filament\Widgets\XotBaseTableWidget;

class QuestionChartDataWidget extends XotBaseTableWidget
{
    protected static ?string $heading = 'Question Answer Data';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('submitdate')
                    ->dateTime()
                    ->sortable()
                    ->searchable(),

                TextColumn::make('answert')
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 50 ? $state : null;
                    })
                    ->searchable(),

                TextColumn::make('answer_lang')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'it' => 'success',
                        'en' => 'info',
                        'fr' => 'warning',
                        'de' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
            ])
            ->defaultSort('submitdate', 'desc')
            ->paginated([10, 25, 50, 100])
            ->poll('30s');
    }

    protected function getTableQuery()
    {
        $record = $this->getRecord();

        if (!$record) {
            return $record->answers()->whereRaw('1 = 0');
        }

        return $record->answers()
            ->select(['submitdate', 'answert', 'answer_lang'])
            ->when($record->date_from, function ($query, $dateFrom) {
                $query->where('submitdate', '>=', $dateFrom);
            })
            ->when($record->date_to, function ($query, $dateTo) {
                $query->where('submitdate', '<=', $dateTo);
            });
    }

    protected function getRecord()
    {
        return $this->getTableRecord();
    }
}
```

### 3. **Widget di Grafico (XotBaseWidget)**
```php
<?php

declare(strict_types=1);

namespace Modules\<nome progetto>\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Modules\Xot\Filament\Widgets\XotBaseWidget;

class QuestionChartWidget extends XotBaseWidget
{
    protected static ?string $heading = 'Question Chart Visualization';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 3;

    public function getFormSchema(): array
    {
        return []; // No form needed for chart widget
    }

    protected function getData(): array
    {
        $record = $this->getRecord();

        if (!$record) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        $chartData = $this->getChartData($record);

        return [
            'datasets' => [
                [
                    'label' => 'Answer Distribution',
                    'data' => $chartData['values'],
                    'backgroundColor' => $this->getChartColors(count($chartData['values'])),
                    'borderColor' => $this->getChartColors(count($chartData['values']), 0.8),
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $chartData['labels'],
        ];
    }

    protected function getType(): string
    {
        $record = $this->getRecord();

        if (!$record) {
            return 'bar';
        }

        return match ($record->chart_type) {
            'pie' => 'pie',
            'doughnut' => 'doughnut',
            'line' => 'line',
            default => 'bar',
        };
    }

    protected function getChartData($record): array
    {
        $answers = $record->answers()
            ->select(['answert', 'answer_lang'])
            ->whereNotNull('answert')
            ->when($record->date_from, function ($query, $dateFrom) {
                $query->where('submitdate', '>=', $dateFrom);
            })
            ->when($record->date_to, function ($query, $dateTo) {
                $query->where('submitdate', '<=', $dateTo);
            })
            ->get();

        $grouped = $answers->groupBy('answer_lang');

        $labels = [];
        $values = [];

        foreach ($grouped as $lang => $langAnswers) {
            $labels[] = $lang ?: 'Unknown';
            $values[] = $langAnswers->count();
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }

    protected function getChartColors(int $count, float $alpha = 1.0): array
    {
        $baseColors = [
            'rgba(54, 162, 235, ' . $alpha . ')',
            'rgba(255, 99, 132, ' . $alpha . ')',
            'rgba(255, 205, 86, ' . $alpha . ')',
            'rgba(75, 192, 192, ' . $alpha . ')',
            'rgba(153, 102, 255, ' . $alpha . ')',
            'rgba(255, 159, 64, ' . $alpha . ')',
            'rgba(199, 199, 199, ' . $alpha . ')',
            'rgba(83, 102, 255, ' . $alpha . ')',
        ];

        $colors = [];
        for ($i = 0; $i < $count; $i++) {
            $colors[] = $baseColors[$i % count($baseColors)];
        }

        return $colors;
    }
}
```

## 🚫 Errori Comuni da Evitare

### 1. **Usare XotBaseWidget per Tabelle**
```php
// ❌ SBAGLIATO
class MyTableWidget extends XotBaseWidget
{
    public function table(Table $table): Table
    {
        // XotBaseWidget richiede getFormSchema()!
    }
}

// ✅ CORRETTO
class MyTableWidget extends XotBaseTableWidget
{
    public function table(Table $table): Table
    {
        // XotBaseTableWidget è per tabelle
    }
}
```

### 2. **Non Implementare Metodi Obbligatori**
```php
// ❌ SBAGLIATO - Manca getFormSchema()
class MyWidget extends XotBaseWidget
{
    // Errore: deve implementare getFormSchema()
}

// ✅ CORRETTO
class MyWidget extends XotBaseWidget
{
    public function getFormSchema(): array
    {
        return [];
    }
}
```

### 3. **Mescolare Responsabilità**
```php
// ❌ SBAGLIATO - Widget che fa tutto
class MyWidget extends XotBaseWidget
{
    public function getFormSchema(): array
    {
        return []; // Form vuoto
    }

    public function table(Table $table): Table
    {
        // Tabella in widget di form
    }
}

// ✅ CORRETTO - Separare responsabilità
class MyFilterWidget extends XotBaseWidget
{
    public function getFormSchema(): array
    {
        return [/* form components */];
    }
}

class MyTableWidget extends XotBaseTableWidget
{
    public function table(Table $table): Table
    {
        return $table->columns(/* table columns */);
    }
}
```

## 📋 Checklist Implementazione

### ✅ Prima di Implementare
- [ ] Decidere se widget ha form o tabella
- [ ] Scegliere XotBaseWidget o XotBaseTableWidget
- [ ] Verificare metodi obbligatori da implementare

### ✅ Durante Implementazione
- [ ] Implementare tutti i metodi obbligatori
- [ ] Seguire convenzioni naming
- [ ] Usare type hints corretti
- [ ] Documentare PHPDoc

### ✅ Dopo Implementazione
- [ ] Verificare PHPStan livello 10
- [ ] Controllare conformità PSR-12
- [ ] Testare funzionalità
- [ ] Aggiornare documentazione

## 📚 Riferimenti

- [XotBaseWidget Implementation](./xotbasewidget_implementation.md)
- [XotBaseTableWidget Implementation](./xotbasetablewidget_implementation.md)
- [Laraxot Architecture Rules](./laraxot_architecture_rules.md)

Queste regole garantiscono implementazione corretta dei widget seguendo l'architettura Laraxot.


---
## Variant 2

# Widget Implementation Rules - Xot Module

## 🎯 Regole Fondamentali per Widget

### 1. **Tipi di Widget Disponibili**

#### **XotBaseWidget** - Per widget con form
```php
// ✅ CORRETTO - Widget con form
class MyFormWidget extends XotBaseWidget
{
    public function getFormSchema(): array
    {
        return [
            // Form components
        ];
    }
}
```

#### **XotBaseTableWidget** - Per widget di tabella
```php
// ✅ CORRETTO - Widget di tabella
class MyTableWidget extends XotBaseTableWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns($this->getTableColumns());
    }
}
```

### 2. **MAI mescolare i tipi**
```php
// ❌ SBAGLIATO - XotBaseWidget per tabella
class MyTableWidget extends XotBaseWidget
{
    public function table(Table $table): Table
    {
        // XotBaseWidget richiede getFormSchema()!
    }
}

// ✅ CORRETTO - XotBaseTableWidget per tabella
class MyTableWidget extends XotBaseTableWidget
{
    public function table(Table $table): Table
    {
        // XotBaseTableWidget è progettato per tabelle
    }
}
```

### 3. **Metodi Obbligatori per Tipo**

#### **XotBaseWidget**
- `getFormSchema(): array` - **OBBLIGATORIO**

#### **XotBaseTableWidget**
- `table(Table $table): Table` - **OBBLIGATORIO**
- `getTableQuery()` - **OBBLIGATORIO**
- `getTableColumns(): array` - **OBBLIGATORIO**

## 🏗️ Implementazione Corretta

### 1. **Widget con Filtri (XotBaseWidget)**
```php
<?php

declare(strict_types=1);

namespace Modules\<nome progetto>\Filament\Widgets;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Modules\Xot\Filament\Widgets\XotBaseWidget;

class QuestionChartFilterWidget extends XotBaseWidget
{
    protected static ?string $heading = 'Filter Options';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 1;

    public ?string $dateFrom = null;
    public ?string $dateTo = null;
    public ?string $answerFilter = null;

    public function getFormSchema(): array
    {
        return [
            DatePicker::make('dateFrom')
                ->live()
                ->afterStateUpdated(fn () => $this->updateFilters()),

            DatePicker::make('dateTo')
                ->live()
                ->afterStateUpdated(fn () => $this->updateFilters()),

            Select::make('answerFilter')
                ->options([
                    'all' => 'All Answers',
                    'answered' => 'Answered Only',
                    'not_answered' => 'Not Answered',
                ])
                ->default('all')
                ->live()
                ->afterStateUpdated(fn () => $this->updateFilters()),
        ];
    }

    public function updateFilters(): void
    {
        $this->dispatch('filters-updated', [
            'dateFrom' => $this->dateFrom,
            'dateTo' => $this->dateTo,
            'answerFilter' => $this->answerFilter,
        ]);
    }
}
```

### 2. **Widget di Tabella (XotBaseTableWidget)**
```php
<?php

declare(strict_types=1);

namespace Modules\<nome progetto>\Filament\Widgets;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Xot\Filament\Widgets\XotBaseTableWidget;

class QuestionChartDataWidget extends XotBaseTableWidget
{
    protected static ?string $heading = 'Question Answer Data';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('submitdate')
                    ->dateTime()
                    ->sortable()
                    ->searchable(),

                TextColumn::make('answert')
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 50 ? $state : null;
                    })
                    ->searchable(),

                TextColumn::make('answer_lang')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'it' => 'success',
                        'en' => 'info',
                        'fr' => 'warning',
                        'de' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
            ])
            ->defaultSort('submitdate', 'desc')
            ->paginated([10, 25, 50, 100])
            ->poll('30s');
    }

    protected function getTableQuery()
    {
        $record = $this->getRecord();

        if (!$record) {
            return $record->answers()->whereRaw('1 = 0');
        }

        return $record->answers()
            ->select(['submitdate', 'answert', 'answer_lang'])
            ->when($record->date_from, function ($query, $dateFrom) {
                $query->where('submitdate', '>=', $dateFrom);
            })
            ->when($record->date_to, function ($query, $dateTo) {
                $query->where('submitdate', '<=', $dateTo);
            });
    }

    protected function getRecord()
    {
        return $this->getTableRecord();
    }
}
```

### 3. **Widget di Grafico (XotBaseWidget)**
```php
<?php

declare(strict_types=1);

namespace Modules\<nome progetto>\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Modules\Xot\Filament\Widgets\XotBaseWidget;

class QuestionChartWidget extends XotBaseWidget
{
    protected static ?string $heading = 'Question Chart Visualization';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 3;

    public function getFormSchema(): array
    {
        return []; // No form needed for chart widget
    }

    protected function getData(): array
    {
        $record = $this->getRecord();

        if (!$record) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        $chartData = $this->getChartData($record);

        return [
            'datasets' => [
                [
                    'label' => 'Answer Distribution',
                    'data' => $chartData['values'],
                    'backgroundColor' => $this->getChartColors(count($chartData['values'])),
                    'borderColor' => $this->getChartColors(count($chartData['values']), 0.8),
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $chartData['labels'],
        ];
    }

    protected function getType(): string
    {
        $record = $this->getRecord();

        if (!$record) {
            return 'bar';
        }

        return match ($record->chart_type) {
            'pie' => 'pie',
            'doughnut' => 'doughnut',
            'line' => 'line',
            default => 'bar',
        };
    }

    protected function getChartData($record): array
    {
        $answers = $record->answers()
            ->select(['answert', 'answer_lang'])
            ->whereNotNull('answert')
            ->when($record->date_from, function ($query, $dateFrom) {
                $query->where('submitdate', '>=', $dateFrom);
            })
            ->when($record->date_to, function ($query, $dateTo) {
                $query->where('submitdate', '<=', $dateTo);
            })
            ->get();

        $grouped = $answers->groupBy('answer_lang');

        $labels = [];
        $values = [];

        foreach ($grouped as $lang => $langAnswers) {
            $labels[] = $lang ?: 'Unknown';
            $values[] = $langAnswers->count();
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }

    protected function getChartColors(int $count, float $alpha = 1.0): array
    {
        $baseColors = [
            'rgba(54, 162, 235, ' . $alpha . ')',
            'rgba(255, 99, 132, ' . $alpha . ')',
            'rgba(255, 205, 86, ' . $alpha . ')',
            'rgba(75, 192, 192, ' . $alpha . ')',
            'rgba(153, 102, 255, ' . $alpha . ')',
            'rgba(255, 159, 64, ' . $alpha . ')',
            'rgba(199, 199, 199, ' . $alpha . ')',
            'rgba(83, 102, 255, ' . $alpha . ')',
        ];

        $colors = [];
        for ($i = 0; $i < $count; $i++) {
            $colors[] = $baseColors[$i % count($baseColors)];
        }

        return $colors;
    }
}
```

## 🚫 Errori Comuni da Evitare

### 1. **Usare XotBaseWidget per Tabelle**
```php
// ❌ SBAGLIATO
class MyTableWidget extends XotBaseWidget
{
    public function table(Table $table): Table
    {
        // XotBaseWidget richiede getFormSchema()!
    }
}

// ✅ CORRETTO
class MyTableWidget extends XotBaseTableWidget
{
    public function table(Table $table): Table
    {
        // XotBaseTableWidget è per tabelle
    }
}
```

### 2. **Non Implementare Metodi Obbligatori**
```php
// ❌ SBAGLIATO - Manca getFormSchema()
class MyWidget extends XotBaseWidget
{
    // Errore: deve implementare getFormSchema()
}

// ✅ CORRETTO
class MyWidget extends XotBaseWidget
{
    public function getFormSchema(): array
    {
        return [];
    }
}
```

### 3. **Mescolare Responsabilità**
```php
// ❌ SBAGLIATO - Widget che fa tutto
class MyWidget extends XotBaseWidget
{
    public function getFormSchema(): array
    {
        return []; // Form vuoto
    }

    public function table(Table $table): Table
    {
        // Tabella in widget di form
    }
}

// ✅ CORRETTO - Separare responsabilità
class MyFilterWidget extends XotBaseWidget
{
    public function getFormSchema(): array
    {
        return [/* form components */];
    }
}

class MyTableWidget extends XotBaseTableWidget
{
    public function table(Table $table): Table
    {
        return $table->columns(/* table columns */);
    }
}
```

## 📋 Checklist Implementazione

### ✅ Prima di Implementare
- [ ] Decidere se widget ha form o tabella
- [ ] Scegliere XotBaseWidget o XotBaseTableWidget
- [ ] Verificare metodi obbligatori da implementare

### ✅ Durante Implementazione
- [ ] Implementare tutti i metodi obbligatori
- [ ] Seguire convenzioni naming
- [ ] Usare type hints corretti
- [ ] Documentare PHPDoc

### ✅ Dopo Implementazione
- [ ] Verificare PHPStan livello 10
- [ ] Controllare conformità PSR-12
- [ ] Testare funzionalità
- [ ] Aggiornare documentazione

## 📚 Riferimenti

- [XotBaseWidget Implementation](./xotbasewidget_implementation.md)
- [XotBaseTableWidget Implementation](./xotbasetablewidget_implementation.md)
- [Laraxot Architecture Rules](./LARAXOT_ARCHITECTURE_RULES.md)

Queste regole garantiscono implementazione corretta dei widget seguendo l'architettura Laraxot.


---
## Merged from widget-implementation-rules_2.md

# Widget Implementation Rules - Xot Module

## 🎯 Regole Fondamentali per Widget

### 1. **Tipi di Widget Disponibili**

#### **XotBaseWidget** - Per widget con form
```php
// ✅ CORRETTO - Widget con form
class MyFormWidget extends XotBaseWidget
{
    public function getFormSchema(): array
    {
        return [
            // Form components
        ];
    }
}
```

#### **XotBaseTableWidget** - Per widget di tabella
```php
// ✅ CORRETTO - Widget di tabella
class MyTableWidget extends XotBaseTableWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns($this->getTableColumns());
    }
}
```

### 2. **MAI mescolare i tipi**
```php
// ❌ SBAGLIATO - XotBaseWidget per tabella
class MyTableWidget extends XotBaseWidget
{
    public function table(Table $table): Table
    {
        // XotBaseWidget richiede getFormSchema()!
    }
}

// ✅ CORRETTO - XotBaseTableWidget per tabella
class MyTableWidget extends XotBaseTableWidget
{
    public function table(Table $table): Table
    {
        // XotBaseTableWidget è progettato per tabelle
    }
}
```

### 3. **Metodi Obbligatori per Tipo**

#### **XotBaseWidget**
- `getFormSchema(): array` - **OBBLIGATORIO**

#### **XotBaseTableWidget**
- `table(Table $table): Table` - **OBBLIGATORIO**
- `getTableQuery()` - **OBBLIGATORIO**
- `getTableColumns(): array` - **OBBLIGATORIO**

## 🏗️ Implementazione Corretta

### 1. **Widget con Filtri (XotBaseWidget)**
```php
<?php

declare(strict_types=1);


use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Modules\Xot\Filament\Widgets\XotBaseWidget;

class QuestionChartFilterWidget extends XotBaseWidget
{
    protected static ?string $heading = 'Filter Options';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 1;

    public ?string $dateFrom = null;
    public ?string $dateTo = null;
    public ?string $answerFilter = null;

    public function getFormSchema(): array
    {
        return [
            DatePicker::make('dateFrom')
                ->live()
                ->afterStateUpdated(fn () => $this->updateFilters()),
            
            DatePicker::make('dateTo')
                ->live()
                ->afterStateUpdated(fn () => $this->updateFilters()),
            
            Select::make('answerFilter')
                ->options([
                    'all' => 'All Answers',
                    'answered' => 'Answered Only',
                    'not_answered' => 'Not Answered',
                ])
                ->default('all')
                ->live()
                ->afterStateUpdated(fn () => $this->updateFilters()),
        ];
    }

    public function updateFilters(): void
    {
        $this->dispatch('filters-updated', [
            'dateFrom' => $this->dateFrom,
            'dateTo' => $this->dateTo,
            'answerFilter' => $this->answerFilter,
        ]);
    }
}
```

### 2. **Widget di Tabella (XotBaseTableWidget)**
```php
<?php

declare(strict_types=1);


use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Xot\Filament\Widgets\XotBaseTableWidget;

class QuestionChartDataWidget extends XotBaseTableWidget
{
    protected static ?string $heading = 'Question Answer Data';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('submitdate')
                    ->dateTime()
                    ->sortable()
                    ->searchable(),
                
                TextColumn::make('answert')
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 50 ? $state : null;
                    })
                    ->searchable(),
                
                TextColumn::make('answer_lang')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'it' => 'success',
                        'en' => 'info',
                        'fr' => 'warning',
                        'de' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
            ])
            ->defaultSort('submitdate', 'desc')
            ->paginated([10, 25, 50, 100])
            ->poll('30s');
    }

    protected function getTableQuery()
    {
        $record = $this->getRecord();
        
        if (!$record) {
            return $record->answers()->whereRaw('1 = 0');
        }
        
        return $record->answers()
            ->select(['submitdate', 'answert', 'answer_lang'])
            ->when($record->date_from, function ($query, $dateFrom) {
                $query->where('submitdate', '>=', $dateFrom);
            })
            ->when($record->date_to, function ($query, $dateTo) {
                $query->where('submitdate', '<=', $dateTo);
            });
    }

    protected function getRecord()
    {
        return $this->getTableRecord();
    }
}
```

### 3. **Widget di Grafico (XotBaseWidget)**
```php
<?php

declare(strict_types=1);


use Filament\Widgets\ChartWidget;
use Modules\Xot\Filament\Widgets\XotBaseWidget;

class QuestionChartWidget extends XotBaseWidget
{
    protected static ?string $heading = 'Question Chart Visualization';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 3;

    public function getFormSchema(): array
    {
        return []; // No form needed for chart widget
    }

    protected function getData(): array
    {
        $record = $this->getRecord();
        
        if (!$record) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }
        
        $chartData = $this->getChartData($record);
        
        return [
            'datasets' => [
                [
                    'label' => 'Answer Distribution',
                    'data' => $chartData['values'],
                    'backgroundColor' => $this->getChartColors(count($chartData['values'])),
                    'borderColor' => $this->getChartColors(count($chartData['values']), 0.8),
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $chartData['labels'],
        ];
    }

    protected function getType(): string
    {
        $record = $this->getRecord();
        
        if (!$record) {
            return 'bar';
        }
        
        return match ($record->chart_type) {
            'pie' => 'pie',
            'doughnut' => 'doughnut',
            'line' => 'line',
            default => 'bar',
        };
    }

    protected function getChartData($record): array
    {
        $answers = $record->answers()
            ->select(['answert', 'answer_lang'])
            ->whereNotNull('answert')
            ->when($record->date_from, function ($query, $dateFrom) {
                $query->where('submitdate', '>=', $dateFrom);
            })
            ->when($record->date_to, function ($query, $dateTo) {
                $query->where('submitdate', '<=', $dateTo);
            })
            ->get();
        
        $grouped = $answers->groupBy('answer_lang');
        
        $labels = [];
        $values = [];
        
        foreach ($grouped as $lang => $langAnswers) {
            $labels[] = $lang ?: 'Unknown';
            $values[] = $langAnswers->count();
        }
        
        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }

    protected function getChartColors(int $count, float $alpha = 1.0): array
    {
        $baseColors = [
            'rgba(54, 162, 235, ' . $alpha . ')',
            'rgba(255, 99, 132, ' . $alpha . ')',
            'rgba(255, 205, 86, ' . $alpha . ')',
            'rgba(75, 192, 192, ' . $alpha . ')',
            'rgba(153, 102, 255, ' . $alpha . ')',
            'rgba(255, 159, 64, ' . $alpha . ')',
            'rgba(199, 199, 199, ' . $alpha . ')',
            'rgba(83, 102, 255, ' . $alpha . ')',
        ];
        
        $colors = [];
        for ($i = 0; $i < $count; $i++) {
            $colors[] = $baseColors[$i % count($baseColors)];
        }
        
        return $colors;
    }
}
```

## 🚫 Errori Comuni da Evitare

### 1. **Usare XotBaseWidget per Tabelle**
```php
// ❌ SBAGLIATO
class MyTableWidget extends XotBaseWidget
{
    public function table(Table $table): Table
    {
        // XotBaseWidget richiede getFormSchema()!
    }
}

// ✅ CORRETTO
class MyTableWidget extends XotBaseTableWidget
{
    public function table(Table $table): Table
    {
        // XotBaseTableWidget è per tabelle
    }
}
```

### 2. **Non Implementare Metodi Obbligatori**
```php
// ❌ SBAGLIATO - Manca getFormSchema()
class MyWidget extends XotBaseWidget
{
    // Errore: deve implementare getFormSchema()
}

// ✅ CORRETTO
class MyWidget extends XotBaseWidget
{
    public function getFormSchema(): array
    {
        return [];
    }
}
```

### 3. **Mescolare Responsabilità**
```php
// ❌ SBAGLIATO - Widget che fa tutto
class MyWidget extends XotBaseWidget
{
    public function getFormSchema(): array
    {
        return []; // Form vuoto
    }
    
    public function table(Table $table): Table
    {
        // Tabella in widget di form
    }
}

// ✅ CORRETTO - Separare responsabilità
class MyFilterWidget extends XotBaseWidget
{
    public function getFormSchema(): array
    {
        return [/* form components */];
    }
}

class MyTableWidget extends XotBaseTableWidget
{
    public function table(Table $table): Table
    {
        return $table->columns(/* table columns */);
    }
}
```

## 📋 Checklist Implementazione

### ✅ Prima di Implementare
- [ ] Decidere se widget ha form o tabella
- [ ] Scegliere XotBaseWidget o XotBaseTableWidget
- [ ] Verificare metodi obbligatori da implementare

### ✅ Durante Implementazione
- [ ] Implementare tutti i metodi obbligatori
- [ ] Seguire convenzioni naming
- [ ] Usare type hints corretti
- [ ] Documentare PHPDoc

### ✅ Dopo Implementazione
- [ ] Verificare PHPStan livello 10
- [ ] Controllare conformità PSR-12
- [ ] Testare funzionalità
- [ ] Aggiornare documentazione

## 📚 Riferimenti

- [XotBaseWidget Implementation](./xotbasewidget_implementation.md)
- [XotBaseTableWidget Implementation](./xotbasetablewidget_implementation.md)
- [Laraxot Architecture Rules](./LARAXOT_ARCHITECTURE_RULES.md)

Queste regole garantiscono implementazione corretta dei widget seguendo l'architettura Laraxot.


---

## widget-implementation-rules-xot-module

*Consolidated from: `widget-implementation-rules-xot-module.md`*


## 🎯 Regole Fondamentali per Widget

### 1. **Tipi di Widget Disponibili**

#### **XotBaseWidget** - Per widget con form
```php
// ✅ CORRETTO - Widget con form
class MyFormWidget extends XotBaseWidget
{
    public function getFormSchema(): array
    {
        return [
            // Form components
        ];
    }
}
```

#### **XotBaseTableWidget** - Per widget di tabella
```php
// ✅ CORRETTO - Widget di tabella
class MyTableWidget extends XotBaseTableWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns($this->getTableColumns());
    }
}
```

### 2. **MAI mescolare i tipi**
```php
// ❌ SBAGLIATO - XotBaseWidget per tabella
class MyTableWidget extends XotBaseWidget
{
    public function table(Table $table): Table
    {
        // XotBaseWidget richiede getFormSchema()!
    }
}

// ✅ CORRETTO - XotBaseTableWidget per tabella
class MyTableWidget extends XotBaseTableWidget
{
    public function table(Table $table): Table
    {
        // XotBaseTableWidget è progettato per tabelle
    }
}
```

### 3. **Metodi Obbligatori per Tipo**

#### **XotBaseWidget**
- `getFormSchema(): array` - **OBBLIGATORIO**

#### **XotBaseTableWidget**
- `table(Table $table): Table` - **OBBLIGATORIO**
- `getTableQuery()` - **OBBLIGATORIO**
- `getTableColumns(): array` - **OBBLIGATORIO**

## 🏗️ Implementazione Corretta

### 1. **Widget con Filtri (XotBaseWidget)**
```php
<?php

declare(strict_types=1);

namespace Modules\<nome progetto>\Filament\Widgets;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Modules\Xot\Filament\Widgets\XotBaseWidget;

class QuestionChartFilterWidget extends XotBaseWidget
{
    protected static ?string $heading = 'Filter Options';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 1;

    public ?string $dateFrom = null;
    public ?string $dateTo = null;
    public ?string $answerFilter = null;

    public function getFormSchema(): array
    {
        return [
            DatePicker::make('dateFrom')
                ->live()
                ->afterStateUpdated(fn () => $this->updateFilters()),

            DatePicker::make('dateTo')
                ->live()
                ->afterStateUpdated(fn () => $this->updateFilters()),

            Select::make('answerFilter')
                ->options([
                    'all' => 'All Answers',
                    'answered' => 'Answered Only',
                    'not_answered' => 'Not Answered',
                ])
                ->default('all')
                ->live()
                ->afterStateUpdated(fn () => $this->updateFilters()),
        ];
    }

    public function updateFilters(): void
    {
        $this->dispatch('filters-updated', [
            'dateFrom' => $this->dateFrom,
            'dateTo' => $this->dateTo,
            'answerFilter' => $this->answerFilter,
        ]);
    }
}
```

### 2. **Widget di Tabella (XotBaseTableWidget)**
```php
<?php

declare(strict_types=1);

namespace Modules\<nome progetto>\Filament\Widgets;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Xot\Filament\Widgets\XotBaseTableWidget;

class QuestionChartDataWidget extends XotBaseTableWidget
{
    protected static ?string $heading = 'Question Answer Data';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('submitdate')
                    ->dateTime()
                    ->sortable()
                    ->searchable(),

                TextColumn::make('answert')
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 50 ? $state : null;
                    })
                    ->searchable(),

                TextColumn::make('answer_lang')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'it' => 'success',
                        'en' => 'info',
                        'fr' => 'warning',
                        'de' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
            ])
            ->defaultSort('submitdate', 'desc')
            ->paginated([10, 25, 50, 100])
            ->poll('30s');
    }

    protected function getTableQuery()
    {
        $record = $this->getRecord();

        if (!$record) {
            return $record->answers()->whereRaw('1 = 0');
        }

        return $record->answers()
            ->select(['submitdate', 'answert', 'answer_lang'])
            ->when($record->date_from, function ($query, $dateFrom) {
                $query->where('submitdate', '>=', $dateFrom);
            })
            ->when($record->date_to, function ($query, $dateTo) {
                $query->where('submitdate', '<=', $dateTo);
            });
    }

    protected function getRecord()
    {
        return $this->getTableRecord();
    }
}
```

### 3. **Widget di Grafico (XotBaseWidget)**
```php
<?php

declare(strict_types=1);

namespace Modules\<nome progetto>\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Modules\Xot\Filament\Widgets\XotBaseWidget;

class QuestionChartWidget extends XotBaseWidget
{
    protected static ?string $heading = 'Question Chart Visualization';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 3;

    public function getFormSchema(): array
    {
        return []; // No form needed for chart widget
    }

    protected function getData(): array
    {
        $record = $this->getRecord();

        if (!$record) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        $chartData = $this->getChartData($record);

        return [
            'datasets' => [
                [
                    'label' => 'Answer Distribution',
                    'data' => $chartData['values'],
                    'backgroundColor' => $this->getChartColors(count($chartData['values'])),
                    'borderColor' => $this->getChartColors(count($chartData['values']), 0.8),
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $chartData['labels'],
        ];
    }

    protected function getType(): string
    {
        $record = $this->getRecord();

        if (!$record) {
            return 'bar';
        }

        return match ($record->chart_type) {
            'pie' => 'pie',
            'doughnut' => 'doughnut',
            'line' => 'line',
            default => 'bar',
        };
    }

    protected function getChartData($record): array
    {
        $answers = $record->answers()
            ->select(['answert', 'answer_lang'])
            ->whereNotNull('answert')
            ->when($record->date_from, function ($query, $dateFrom) {
                $query->where('submitdate', '>=', $dateFrom);
            })
            ->when($record->date_to, function ($query, $dateTo) {
                $query->where('submitdate', '<=', $dateTo);
            })
            ->get();

        $grouped = $answers->groupBy('answer_lang');

        $labels = [];
        $values = [];

        foreach ($grouped as $lang => $langAnswers) {
            $labels[] = $lang ?: 'Unknown';
            $values[] = $langAnswers->count();
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }

    protected function getChartColors(int $count, float $alpha = 1.0): array
    {
        $baseColors = [
            'rgba(54, 162, 235, ' . $alpha . ')',
            'rgba(255, 99, 132, ' . $alpha . ')',
            'rgba(255, 205, 86, ' . $alpha . ')',
            'rgba(75, 192, 192, ' . $alpha . ')',
            'rgba(153, 102, 255, ' . $alpha . ')',
            'rgba(255, 159, 64, ' . $alpha . ')',
            'rgba(199, 199, 199, ' . $alpha . ')',
            'rgba(83, 102, 255, ' . $alpha . ')',
        ];

        $colors = [];
        for ($i = 0; $i < $count; $i++) {
            $colors[] = $baseColors[$i % count($baseColors)];
        }

        return $colors;
    }
}
```

## 🚫 Errori Comuni da Evitare

### 1. **Usare XotBaseWidget per Tabelle**
```php
// ❌ SBAGLIATO
class MyTableWidget extends XotBaseWidget
{
    public function table(Table $table): Table
    {
        // XotBaseWidget richiede getFormSchema()!
    }
}

// ✅ CORRETTO
class MyTableWidget extends XotBaseTableWidget
{
    public function table(Table $table): Table
    {
        // XotBaseTableWidget è per tabelle
    }
}
```

### 2. **Non Implementare Metodi Obbligatori**
```php
// ❌ SBAGLIATO - Manca getFormSchema()
class MyWidget extends XotBaseWidget
{
    // Errore: deve implementare getFormSchema()
}

// ✅ CORRETTO
class MyWidget extends XotBaseWidget
{
    public function getFormSchema(): array
    {
        return [];
    }
}
```

### 3. **Mescolare Responsabilità**
```php
// ❌ SBAGLIATO - Widget che fa tutto
class MyWidget extends XotBaseWidget
{
    public function getFormSchema(): array
    {
        return []; // Form vuoto
    }

    public function table(Table $table): Table
    {
        // Tabella in widget di form
    }
}

// ✅ CORRETTO - Separare responsabilità
class MyFilterWidget extends XotBaseWidget
{
    public function getFormSchema(): array
    {
        return [/* form components */];
    }
}

class MyTableWidget extends XotBaseTableWidget
{
    public function table(Table $table): Table
    {
        return $table->columns(/* table columns */);
    }
}
```

## 📋 Checklist Implementazione

### ✅ Prima di Implementare
- [ ] Decidere se widget ha form o tabella
- [ ] Scegliere XotBaseWidget o XotBaseTableWidget
- [ ] Verificare metodi obbligatori da implementare

### ✅ Durante Implementazione
- [ ] Implementare tutti i metodi obbligatori
- [ ] Seguire convenzioni naming
- [ ] Usare type hints corretti
- [ ] Documentare PHPDoc

### ✅ Dopo Implementazione
- [ ] Verificare PHPStan livello 10
- [ ] Controllare conformità PSR-12
- [ ] Testare funzionalità
- [ ] Aggiornare documentazione

## 📚 Riferimenti

- [XotBaseWidget Implementation](./xotbasewidget_implementation.md)
- [XotBaseTableWidget Implementation](./xotbasetablewidget_implementation.md)
- [Laraxot Architecture Rules](./LARAXOT_ARCHITECTURE_RULES.md)

Queste regole garantiscono implementazione corretta dei widget seguendo l'architettura Laraxot.


---
## Merged from widget-implementation-rules_2.md

# Widget Implementation Rules - Xot Module

## 🎯 Regole Fondamentali per Widget

### 1. **Tipi di Widget Disponibili**

#### **XotBaseWidget** - Per widget con form
```php
// ✅ CORRETTO - Widget con form
class MyFormWidget extends XotBaseWidget
{
    public function getFormSchema(): array
    {
        return [
            // Form components
        ];
    }
}
```

#### **XotBaseTableWidget** - Per widget di tabella
```php
// ✅ CORRETTO - Widget di tabella
class MyTableWidget extends XotBaseTableWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns($this->getTableColumns());
    }
}
```

### 2. **MAI mescolare i tipi**
```php
// ❌ SBAGLIATO - XotBaseWidget per tabella
class MyTableWidget extends XotBaseWidget
{
    public function table(Table $table): Table
    {
        // XotBaseWidget richiede getFormSchema()!
    }
}

// ✅ CORRETTO - XotBaseTableWidget per tabella
class MyTableWidget extends XotBaseTableWidget
{
    public function table(Table $table): Table
    {
        // XotBaseTableWidget è progettato per tabelle
    }
}
```

### 3. **Metodi Obbligatori per Tipo**

#### **XotBaseWidget**
- `getFormSchema(): array` - **OBBLIGATORIO**

#### **XotBaseTableWidget**
- `table(Table $table): Table` - **OBBLIGATORIO**
- `getTableQuery()` - **OBBLIGATORIO**
- `getTableColumns(): array` - **OBBLIGATORIO**

## 🏗️ Implementazione Corretta

### 1. **Widget con Filtri (XotBaseWidget)**
```php
<?php

declare(strict_types=1);


use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Modules\Xot\Filament\Widgets\XotBaseWidget;

class QuestionChartFilterWidget extends XotBaseWidget
{
    protected static ?string $heading = 'Filter Options';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 1;

    public ?string $dateFrom = null;
    public ?string $dateTo = null;
    public ?string $answerFilter = null;

    public function getFormSchema(): array
    {
        return [
            DatePicker::make('dateFrom')
                ->live()
                ->afterStateUpdated(fn () => $this->updateFilters()),
            
            DatePicker::make('dateTo')
                ->live()
                ->afterStateUpdated(fn () => $this->updateFilters()),
            
            Select::make('answerFilter')
                ->options([
                    'all' => 'All Answers',
                    'answered' => 'Answered Only',
                    'not_answered' => 'Not Answered',
                ])
                ->default('all')
                ->live()
                ->afterStateUpdated(fn () => $this->updateFilters()),
        ];
    }

    public function updateFilters(): void
    {
        $this->dispatch('filters-updated', [
            'dateFrom' => $this->dateFrom,
            'dateTo' => $this->dateTo,
            'answerFilter' => $this->answerFilter,
        ]);
    }
}
```

### 2. **Widget di Tabella (XotBaseTableWidget)**
```php
<?php

declare(strict_types=1);


use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Xot\Filament\Widgets\XotBaseTableWidget;

class QuestionChartDataWidget extends XotBaseTableWidget
{
    protected static ?string $heading = 'Question Answer Data';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('submitdate')
                    ->dateTime()
                    ->sortable()
                    ->searchable(),
                
                TextColumn::make('answert')
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 50 ? $state : null;
                    })
                    ->searchable(),
                
                TextColumn::make('answer_lang')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'it' => 'success',
                        'en' => 'info',
                        'fr' => 'warning',
                        'de' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
            ])
            ->defaultSort('submitdate', 'desc')
            ->paginated([10, 25, 50, 100])
            ->poll('30s');
    }

    protected function getTableQuery()
    {
        $record = $this->getRecord();
        
        if (!$record) {
            return $record->answers()->whereRaw('1 = 0');
        }
        
        return $record->answers()
            ->select(['submitdate', 'answert', 'answer_lang'])
            ->when($record->date_from, function ($query, $dateFrom) {
                $query->where('submitdate', '>=', $dateFrom);
            })
            ->when($record->date_to, function ($query, $dateTo) {
                $query->where('submitdate', '<=', $dateTo);
            });
    }

    protected function getRecord()
    {
        return $this->getTableRecord();
    }
}
```

### 3. **Widget di Grafico (XotBaseWidget)**
```php
<?php

declare(strict_types=1);


use Filament\Widgets\ChartWidget;
use Modules\Xot\Filament\Widgets\XotBaseWidget;

class QuestionChartWidget extends XotBaseWidget
{
    protected static ?string $heading = 'Question Chart Visualization';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 3;

    public function getFormSchema(): array
    {
        return []; // No form needed for chart widget
    }

    protected function getData(): array
    {
        $record = $this->getRecord();
        
        if (!$record) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }
        
        $chartData = $this->getChartData($record);
        
        return [
            'datasets' => [
                [
                    'label' => 'Answer Distribution',
                    'data' => $chartData['values'],
                    'backgroundColor' => $this->getChartColors(count($chartData['values'])),
                    'borderColor' => $this->getChartColors(count($chartData['values']), 0.8),
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $chartData['labels'],
        ];
    }

    protected function getType(): string
    {
        $record = $this->getRecord();
        
        if (!$record) {
            return 'bar';
        }
        
        return match ($record->chart_type) {
            'pie' => 'pie',
            'doughnut' => 'doughnut',
            'line' => 'line',
            default => 'bar',
        };
    }

    protected function getChartData($record): array
    {
        $answers = $record->answers()
            ->select(['answert', 'answer_lang'])
            ->whereNotNull('answert')
            ->when($record->date_from, function ($query, $dateFrom) {
                $query->where('submitdate', '>=', $dateFrom);
            })
            ->when($record->date_to, function ($query, $dateTo) {
                $query->where('submitdate', '<=', $dateTo);
            })
            ->get();
        
        $grouped = $answers->groupBy('answer_lang');
        
        $labels = [];
        $values = [];
        
        foreach ($grouped as $lang => $langAnswers) {
            $labels[] = $lang ?: 'Unknown';
            $values[] = $langAnswers->count();
        }
        
        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }

    protected function getChartColors(int $count, float $alpha = 1.0): array
    {
        $baseColors = [
            'rgba(54, 162, 235, ' . $alpha . ')',
            'rgba(255, 99, 132, ' . $alpha . ')',
            'rgba(255, 205, 86, ' . $alpha . ')',
            'rgba(75, 192, 192, ' . $alpha . ')',
            'rgba(153, 102, 255, ' . $alpha . ')',
            'rgba(255, 159, 64, ' . $alpha . ')',
            'rgba(199, 199, 199, ' . $alpha . ')',
            'rgba(83, 102, 255, ' . $alpha . ')',
        ];
        
        $colors = [];
        for ($i = 0; $i < $count; $i++) {
            $colors[] = $baseColors[$i % count($baseColors)];
        }
        
        return $colors;
    }
}
```

## 🚫 Errori Comuni da Evitare

### 1. **Usare XotBaseWidget per Tabelle**
```php
// ❌ SBAGLIATO
class MyTableWidget extends XotBaseWidget
{
    public function table(Table $table): Table
    {
        // XotBaseWidget richiede getFormSchema()!
    }
}

// ✅ CORRETTO
class MyTableWidget extends XotBaseTableWidget
{
    public function table(Table $table): Table
    {
        // XotBaseTableWidget è per tabelle
    }
}
```

### 2. **Non Implementare Metodi Obbligatori**
```php
// ❌ SBAGLIATO - Manca getFormSchema()
class MyWidget extends XotBaseWidget
{
    // Errore: deve implementare getFormSchema()
}

// ✅ CORRETTO
class MyWidget extends XotBaseWidget
{
    public function getFormSchema(): array
    {
        return [];
    }
}
```

### 3. **Mescolare Responsabilità**
```php
// ❌ SBAGLIATO - Widget che fa tutto
class MyWidget extends XotBaseWidget
{
    public function getFormSchema(): array
    {
        return []; // Form vuoto
    }
    
    public function table(Table $table): Table
    {
        // Tabella in widget di form
    }
}

// ✅ CORRETTO - Separare responsabilità
class MyFilterWidget extends XotBaseWidget
{
    public function getFormSchema(): array
    {
        return [/* form components */];
    }
}

class MyTableWidget extends XotBaseTableWidget
{
    public function table(Table $table): Table
    {
        return $table->columns(/* table columns */);
    }
}
```

## 📋 Checklist Implementazione

### ✅ Prima di Implementare
- [ ] Decidere se widget ha form o tabella
- [ ] Scegliere XotBaseWidget o XotBaseTableWidget
- [ ] Verificare metodi obbligatori da implementare

### ✅ Durante Implementazione
- [ ] Implementare tutti i metodi obbligatori
- [ ] Seguire convenzioni naming
- [ ] Usare type hints corretti
- [ ] Documentare PHPDoc

### ✅ Dopo Implementazione
- [ ] Verificare PHPStan livello 10
- [ ] Controllare conformità PSR-12
- [ ] Testare funzionalità
- [ ] Aggiornare documentazione

## 📚 Riferimenti

- [XotBaseWidget Implementation](./xotbasewidget_implementation.md)
- [XotBaseTableWidget Implementation](./xotbasetablewidget_implementation.md)
- [Laraxot Architecture Rules](./LARAXOT_ARCHITECTURE_RULES.md)

Queste regole garantiscono implementazione corretta dei widget seguendo l'architettura Laraxot.

---

## widget-implementation-rules

*Consolidated from: `widget-implementation-rules.md`*


## 🎯 Regole Fondamentali per Widget

### 1. **Tipi di Widget Disponibili**

#### **XotBaseWidget** - Per widget con form
```php
// ✅ CORRETTO - Widget con form
class MyFormWidget extends XotBaseWidget
{
    public function getFormSchema(): array
    {
        return [
            // Form components
        ];
    }
}
```

#### **XotBaseTableWidget** - Per widget di tabella
```php
// ✅ CORRETTO - Widget di tabella
class MyTableWidget extends XotBaseTableWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns($this->getTableColumns());
    }
}
```

### 2. **MAI mescolare i tipi**
```php
// ❌ SBAGLIATO - XotBaseWidget per tabella
class MyTableWidget extends XotBaseWidget
{
    public function table(Table $table): Table
    {
        // XotBaseWidget richiede getFormSchema()!
    }
}

// ✅ CORRETTO - XotBaseTableWidget per tabella
class MyTableWidget extends XotBaseTableWidget
{
    public function table(Table $table): Table
    {
        // XotBaseTableWidget è progettato per tabelle
    }
}
```

### 3. **Metodi Obbligatori per Tipo**

#### **XotBaseWidget**
- `getFormSchema(): array` - **OBBLIGATORIO**

#### **XotBaseTableWidget**
- `table(Table $table): Table` - **OBBLIGATORIO**
- `getTableQuery()` - **OBBLIGATORIO**
- `getTableColumns(): array` - **OBBLIGATORIO**

## 🏗️ Implementazione Corretta

### 1. **Widget con Filtri (XotBaseWidget)**
```php
<?php

declare(strict_types=1);

namespace Modules\<nome progetto>\Filament\Widgets;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Modules\Xot\Filament\Widgets\XotBaseWidget;

class QuestionChartFilterWidget extends XotBaseWidget
{
    protected static ?string $heading = 'Filter Options';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 1;

    public ?string $dateFrom = null;
    public ?string $dateTo = null;
    public ?string $answerFilter = null;

    public function getFormSchema(): array
    {
        return [
            DatePicker::make('dateFrom')
                ->live()
                ->afterStateUpdated(fn () => $this->updateFilters()),

            DatePicker::make('dateTo')
                ->live()
                ->afterStateUpdated(fn () => $this->updateFilters()),

            Select::make('answerFilter')
                ->options([
                    'all' => 'All Answers',
                    'answered' => 'Answered Only',
                    'not_answered' => 'Not Answered',
                ])
                ->default('all')
                ->live()
                ->afterStateUpdated(fn () => $this->updateFilters()),
        ];
    }

    public function updateFilters(): void
    {
        $this->dispatch('filters-updated', [
            'dateFrom' => $this->dateFrom,
            'dateTo' => $this->dateTo,
            'answerFilter' => $this->answerFilter,
        ]);
    }
}
```

### 2. **Widget di Tabella (XotBaseTableWidget)**
```php
<?php

declare(strict_types=1);

namespace Modules\<nome progetto>\Filament\Widgets;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Xot\Filament\Widgets\XotBaseTableWidget;

class QuestionChartDataWidget extends XotBaseTableWidget
{
    protected static ?string $heading = 'Question Answer Data';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('submitdate')
                    ->dateTime()
                    ->sortable()
                    ->searchable(),

                TextColumn::make('answert')
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 50 ? $state : null;
                    })
                    ->searchable(),

                TextColumn::make('answer_lang')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'it' => 'success',
                        'en' => 'info',
                        'fr' => 'warning',
                        'de' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
            ])
            ->defaultSort('submitdate', 'desc')
            ->paginated([10, 25, 50, 100])
            ->poll('30s');
    }

    protected function getTableQuery()
    {
        $record = $this->getRecord();

        if (!$record) {
            return $record->answers()->whereRaw('1 = 0');
        }

        return $record->answers()
            ->select(['submitdate', 'answert', 'answer_lang'])
            ->when($record->date_from, function ($query, $dateFrom) {
                $query->where('submitdate', '>=', $dateFrom);
            })
            ->when($record->date_to, function ($query, $dateTo) {
                $query->where('submitdate', '<=', $dateTo);
            });
    }

    protected function getRecord()
    {
        return $this->getTableRecord();
    }
}
```

### 3. **Widget di Grafico (XotBaseWidget)**
```php
<?php

declare(strict_types=1);

namespace Modules\<nome progetto>\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Modules\Xot\Filament\Widgets\XotBaseWidget;

class QuestionChartWidget extends XotBaseWidget
{
    protected static ?string $heading = 'Question Chart Visualization';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 3;

    public function getFormSchema(): array
    {
        return []; // No form needed for chart widget
    }

    protected function getData(): array
    {
        $record = $this->getRecord();

        if (!$record) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        $chartData = $this->getChartData($record);

        return [
            'datasets' => [
                [
                    'label' => 'Answer Distribution',
                    'data' => $chartData['values'],
                    'backgroundColor' => $this->getChartColors(count($chartData['values'])),
                    'borderColor' => $this->getChartColors(count($chartData['values']), 0.8),
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $chartData['labels'],
        ];
    }

    protected function getType(): string
    {
        $record = $this->getRecord();

        if (!$record) {
            return 'bar';
        }

        return match ($record->chart_type) {
            'pie' => 'pie',
            'doughnut' => 'doughnut',
            'line' => 'line',
            default => 'bar',
        };
    }

    protected function getChartData($record): array
    {
        $answers = $record->answers()
            ->select(['answert', 'answer_lang'])
            ->whereNotNull('answert')
            ->when($record->date_from, function ($query, $dateFrom) {
                $query->where('submitdate', '>=', $dateFrom);
            })
            ->when($record->date_to, function ($query, $dateTo) {
                $query->where('submitdate', '<=', $dateTo);
            })
            ->get();

        $grouped = $answers->groupBy('answer_lang');

        $labels = [];
        $values = [];

        foreach ($grouped as $lang => $langAnswers) {
            $labels[] = $lang ?: 'Unknown';
            $values[] = $langAnswers->count();
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }

    protected function getChartColors(int $count, float $alpha = 1.0): array
    {
        $baseColors = [
            'rgba(54, 162, 235, ' . $alpha . ')',
            'rgba(255, 99, 132, ' . $alpha . ')',
            'rgba(255, 205, 86, ' . $alpha . ')',
            'rgba(75, 192, 192, ' . $alpha . ')',
            'rgba(153, 102, 255, ' . $alpha . ')',
            'rgba(255, 159, 64, ' . $alpha . ')',
            'rgba(199, 199, 199, ' . $alpha . ')',
            'rgba(83, 102, 255, ' . $alpha . ')',
        ];

        $colors = [];
        for ($i = 0; $i < $count; $i++) {
            $colors[] = $baseColors[$i % count($baseColors)];
        }

        return $colors;
    }
}
```

## 🚫 Errori Comuni da Evitare

### 1. **Usare XotBaseWidget per Tabelle**
```php
// ❌ SBAGLIATO
class MyTableWidget extends XotBaseWidget
{
    public function table(Table $table): Table
    {
        // XotBaseWidget richiede getFormSchema()!
    }
}

// ✅ CORRETTO
class MyTableWidget extends XotBaseTableWidget
{
    public function table(Table $table): Table
    {
        // XotBaseTableWidget è per tabelle
    }
}
```

### 2. **Non Implementare Metodi Obbligatori**
```php
// ❌ SBAGLIATO - Manca getFormSchema()
class MyWidget extends XotBaseWidget
{
    // Errore: deve implementare getFormSchema()
}

// ✅ CORRETTO
class MyWidget extends XotBaseWidget
{
    public function getFormSchema(): array
    {
        return [];
    }
}
```

### 3. **Mescolare Responsabilità**
```php
// ❌ SBAGLIATO - Widget che fa tutto
class MyWidget extends XotBaseWidget
{
    public function getFormSchema(): array
    {
        return []; // Form vuoto
    }

    public function table(Table $table): Table
    {
        // Tabella in widget di form
    }
}

// ✅ CORRETTO - Separare responsabilità
class MyFilterWidget extends XotBaseWidget
{
    public function getFormSchema(): array
    {
        return [/* form components */];
    }
}

class MyTableWidget extends XotBaseTableWidget
{
    public function table(Table $table): Table
    {
        return $table->columns(/* table columns */);
    }
}
```

## 📋 Checklist Implementazione

### ✅ Prima di Implementare
- [ ] Decidere se widget ha form o tabella
- [ ] Scegliere XotBaseWidget o XotBaseTableWidget
- [ ] Verificare metodi obbligatori da implementare

### ✅ Durante Implementazione
- [ ] Implementare tutti i metodi obbligatori
- [ ] Seguire convenzioni naming
- [ ] Usare type hints corretti
- [ ] Documentare PHPDoc

### ✅ Dopo Implementazione
- [ ] Verificare PHPStan livello 10
- [ ] Controllare conformità PSR-12
- [ ] Testare funzionalità
- [ ] Aggiornare documentazione

## 📚 Riferimenti

- [XotBaseWidget Implementation](./xotbasewidget_implementation.md)
- [XotBaseTableWidget Implementation](./xotbasetablewidget_implementation.md)
- [Laraxot Architecture Rules](./LARAXOT_ARCHITECTURE_RULES.md)

Queste regole garantiscono implementazione corretta dei widget seguendo l'architettura Laraxot.
# Widget Implementation Rules - Xot Module

## 🎯 Regole Fondamentali per Widget

### 1. **Tipi di Widget Disponibili**

#### **XotBaseWidget** - Per widget con form
```php
// ✅ CORRETTO - Widget con form
class MyFormWidget extends XotBaseWidget
{
    public function getFormSchema(): array
    {
        return [
            // Form components
        ];
    }
}
```

#### **XotBaseTableWidget** - Per widget di tabella
```php
// ✅ CORRETTO - Widget di tabella
class MyTableWidget extends XotBaseTableWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns($this->getTableColumns());
    }
}
```

### 2. **MAI mescolare i tipi**
```php
// ❌ SBAGLIATO - XotBaseWidget per tabella
class MyTableWidget extends XotBaseWidget
{
    public function table(Table $table): Table
    {
        // XotBaseWidget richiede getFormSchema()!
    }
}

// ✅ CORRETTO - XotBaseTableWidget per tabella
class MyTableWidget extends XotBaseTableWidget
{
    public function table(Table $table): Table
    {
        // XotBaseTableWidget è progettato per tabelle
    }
}
```

### 3. **Metodi Obbligatori per Tipo**

#### **XotBaseWidget**
- `getFormSchema(): array` - **OBBLIGATORIO**

#### **XotBaseTableWidget**
- `table(Table $table): Table` - **OBBLIGATORIO**
- `getTableQuery()` - **OBBLIGATORIO**
- `getTableColumns(): array` - **OBBLIGATORIO**

## 🏗️ Implementazione Corretta

### 1. **Widget con Filtri (XotBaseWidget)**
```php
<?php

declare(strict_types=1);

namespace Modules\SurveyModule\Filament\Widgets;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Modules\Xot\Filament\Widgets\XotBaseWidget;

class QuestionChartFilterWidget extends XotBaseWidget
{
    protected static ?string $heading = 'Filter Options';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 1;

    public ?string $dateFrom = null;
    public ?string $dateTo = null;
    public ?string $answerFilter = null;

    public function getFormSchema(): array
    {
        return [
            DatePicker::make('dateFrom')
                ->live()
                ->afterStateUpdated(fn () => $this->updateFilters()),

            DatePicker::make('dateTo')
                ->live()
                ->afterStateUpdated(fn () => $this->updateFilters()),

            Select::make('answerFilter')
                ->options([
                    'all' => 'All Answers',
                    'answered' => 'Answered Only',
                    'not_answered' => 'Not Answered',
                ])
                ->default('all')
                ->live()
                ->afterStateUpdated(fn () => $this->updateFilters()),
        ];
    }

    public function updateFilters(): void
    {
        $this->dispatch('filters-updated', [
            'dateFrom' => $this->dateFrom,
            'dateTo' => $this->dateTo,
            'answerFilter' => $this->answerFilter,
        ]);
    }
}
```

### 2. **Widget di Tabella (XotBaseTableWidget)**
```php
<?php

declare(strict_types=1);

namespace Modules\SurveyModule\Filament\Widgets;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Xot\Filament\Widgets\XotBaseTableWidget;

class QuestionChartDataWidget extends XotBaseTableWidget
{
    protected static ?string $heading = 'Question Answer Data';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('submitdate')
                    ->dateTime()
                    ->sortable()
                    ->searchable(),

                TextColumn::make('answert')
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 50 ? $state : null;
                    })
                    ->searchable(),

                TextColumn::make('answer_lang')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'it' => 'success',
                        'en' => 'info',
                        'fr' => 'warning',
                        'de' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
            ])
            ->defaultSort('submitdate', 'desc')
            ->paginated([10, 25, 50, 100])
            ->poll('30s');
    }

    protected function getTableQuery()
    {
        $record = $this->getRecord();

        if (!$record) {
            return $record->answers()->whereRaw('1 = 0');
        }

        return $record->answers()
            ->select(['submitdate', 'answert', 'answer_lang'])
            ->when($record->date_from, function ($query, $dateFrom) {
                $query->where('submitdate', '>=', $dateFrom);
            })
            ->when($record->date_to, function ($query, $dateTo) {
                $query->where('submitdate', '<=', $dateTo);
            });
    }

    protected function getRecord()
    {
        return $this->getTableRecord();
    }
}
```

### 3. **Widget di Grafico (XotBaseWidget)**
```php
<?php

declare(strict_types=1);

namespace Modules\SurveyModule\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Modules\Xot\Filament\Widgets\XotBaseWidget;

class QuestionChartWidget extends XotBaseWidget
{
    protected static ?string $heading = 'Question Chart Visualization';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 3;

    public function getFormSchema(): array
    {
        return []; // No form needed for chart widget
    }

    protected function getData(): array
    {
        $record = $this->getRecord();

        if (!$record) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        $chartData = $this->getChartData($record);

        return [
            'datasets' => [
                [
                    'label' => 'Answer Distribution',
                    'data' => $chartData['values'],
                    'backgroundColor' => $this->getChartColors(count($chartData['values'])),
                    'borderColor' => $this->getChartColors(count($chartData['values']), 0.8),
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $chartData['labels'],
        ];
    }

    protected function getType(): string
    {
        $record = $this->getRecord();

        if (!$record) {
            return 'bar';
        }

        return match ($record->chart_type) {
            'pie' => 'pie',
            'doughnut' => 'doughnut',
            'line' => 'line',
            default => 'bar',
        };
    }

    protected function getChartData($record): array
    {
        $answers = $record->answers()
            ->select(['answert', 'answer_lang'])
            ->whereNotNull('answert')
            ->when($record->date_from, function ($query, $dateFrom) {
                $query->where('submitdate', '>=', $dateFrom);
            })
            ->when($record->date_to, function ($query, $dateTo) {
                $query->where('submitdate', '<=', $dateTo);
            })
            ->get();

        $grouped = $answers->groupBy('answer_lang');

        $labels = [];
        $values = [];

        foreach ($grouped as $lang => $langAnswers) {
            $labels[] = $lang ?: 'Unknown';
            $values[] = $langAnswers->count();
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }

    protected function getChartColors(int $count, float $alpha = 1.0): array
    {
        $baseColors = [
            'rgba(54, 162, 235, ' . $alpha . ')',
            'rgba(255, 99, 132, ' . $alpha . ')',
            'rgba(255, 205, 86, ' . $alpha . ')',
            'rgba(75, 192, 192, ' . $alpha . ')',
            'rgba(153, 102, 255, ' . $alpha . ')',
            'rgba(255, 159, 64, ' . $alpha . ')',
            'rgba(199, 199, 199, ' . $alpha . ')',
            'rgba(83, 102, 255, ' . $alpha . ')',
        ];

        $colors = [];
        for ($i = 0; $i < $count; $i++) {
            $colors[] = $baseColors[$i % count($baseColors)];
        }

        return $colors;
    }
}
```

## 🚫 Errori Comuni da Evitare

### 1. **Usare XotBaseWidget per Tabelle**
```php
// ❌ SBAGLIATO
class MyTableWidget extends XotBaseWidget
{
    public function table(Table $table): Table
    {
        // XotBaseWidget richiede getFormSchema()!
    }
}

// ✅ CORRETTO
class MyTableWidget extends XotBaseTableWidget
{
    public function table(Table $table): Table
    {
        // XotBaseTableWidget è per tabelle
    }
}
```

### 2. **Non Implementare Metodi Obbligatori**
```php
// ❌ SBAGLIATO - Manca getFormSchema()
class MyWidget extends XotBaseWidget
{
    // Errore: deve implementare getFormSchema()
}

// ✅ CORRETTO
class MyWidget extends XotBaseWidget
{
    public function getFormSchema(): array
    {
        return [];
    }
}
```

### 3. **Mescolare Responsabilità**
```php
// ❌ SBAGLIATO - Widget che fa tutto
class MyWidget extends XotBaseWidget
{
    public function getFormSchema(): array
    {
        return []; // Form vuoto
    }

    public function table(Table $table): Table
    {
        // Tabella in widget di form
    }
}

// ✅ CORRETTO - Separare responsabilità
class MyFilterWidget extends XotBaseWidget
{
    public function getFormSchema(): array
    {
        return [/* form components */];
    }
}

class MyTableWidget extends XotBaseTableWidget
{
    public function table(Table $table): Table
    {
        return $table->columns(/* table columns */);
    }
}
```

## 📋 Checklist Implementazione

### ✅ Prima di Implementare
- [ ] Decidere se widget ha form o tabella
- [ ] Scegliere XotBaseWidget o XotBaseTableWidget
- [ ] Verificare metodi obbligatori da implementare

### ✅ Durante Implementazione
- [ ] Implementare tutti i metodi obbligatori
- [ ] Seguire convenzioni naming
- [ ] Usare type hints corretti
- [ ] Documentare PHPDoc

### ✅ Dopo Implementazione
- [ ] Verificare PHPStan livello 10
- [ ] Controllare conformità PSR-12
- [ ] Testare funzionalità
- [ ] Aggiornare documentazione

## 📚 Riferimenti

- [XotBaseWidget Implementation](./xotbasewidget_implementation.md)
- [XotBaseTableWidget Implementation](./xotbasetablewidget_implementation.md)
- [Laraxot Architecture Rules](./LARAXOT_ARCHITECTURE_RULES.md)

Queste regole garantiscono implementazione corretta dei widget seguendo l'architettura Laraxot.

---

## widget-implementation-rules_2

*Consolidated from: `widget-implementation-rules_2.md`*


## 🎯 Regole Fondamentali per Widget

### 1. **Tipi di Widget Disponibili**

#### **XotBaseWidget** - Per widget con form
```php
// ✅ CORRETTO - Widget con form
class MyFormWidget extends XotBaseWidget
{
    public function getFormSchema(): array
    {
        return [
            // Form components
        ];
    }
}
```

#### **XotBaseTableWidget** - Per widget di tabella
```php
// ✅ CORRETTO - Widget di tabella
class MyTableWidget extends XotBaseTableWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns($this->getTableColumns());
    }
}
```

### 2. **MAI mescolare i tipi**
```php
// ❌ SBAGLIATO - XotBaseWidget per tabella
class MyTableWidget extends XotBaseWidget
{
    public function table(Table $table): Table
    {
        // XotBaseWidget richiede getFormSchema()!
    }
}

// ✅ CORRETTO - XotBaseTableWidget per tabella
class MyTableWidget extends XotBaseTableWidget
{
    public function table(Table $table): Table
    {
        // XotBaseTableWidget è progettato per tabelle
    }
}
```

### 3. **Metodi Obbligatori per Tipo**

#### **XotBaseWidget**
- `getFormSchema(): array` - **OBBLIGATORIO**

#### **XotBaseTableWidget**
- `table(Table $table): Table` - **OBBLIGATORIO**
- `getTableQuery()` - **OBBLIGATORIO**
- `getTableColumns(): array` - **OBBLIGATORIO**

## 🏗️ Implementazione Corretta

### 1. **Widget con Filtri (XotBaseWidget)**
```php
<?php

declare(strict_types=1);


use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Modules\Xot\Filament\Widgets\XotBaseWidget;

class QuestionChartFilterWidget extends XotBaseWidget
{
    protected static ?string $heading = 'Filter Options';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 1;

    public ?string $dateFrom = null;
    public ?string $dateTo = null;
    public ?string $answerFilter = null;

    public function getFormSchema(): array
    {
        return [
            DatePicker::make('dateFrom')
                ->live()
                ->afterStateUpdated(fn () => $this->updateFilters()),
            
            DatePicker::make('dateTo')
                ->live()
                ->afterStateUpdated(fn () => $this->updateFilters()),
            
            Select::make('answerFilter')
                ->options([
                    'all' => 'All Answers',
                    'answered' => 'Answered Only',
                    'not_answered' => 'Not Answered',
                ])
                ->default('all')
                ->live()
                ->afterStateUpdated(fn () => $this->updateFilters()),
        ];
    }

    public function updateFilters(): void
    {
        $this->dispatch('filters-updated', [
            'dateFrom' => $this->dateFrom,
            'dateTo' => $this->dateTo,
            'answerFilter' => $this->answerFilter,
        ]);
    }
}
```

### 2. **Widget di Tabella (XotBaseTableWidget)**
```php
<?php

declare(strict_types=1);


use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Xot\Filament\Widgets\XotBaseTableWidget;

class QuestionChartDataWidget extends XotBaseTableWidget
{
    protected static ?string $heading = 'Question Answer Data';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('submitdate')
                    ->dateTime()
                    ->sortable()
                    ->searchable(),
                
                TextColumn::make('answert')
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 50 ? $state : null;
                    })
                    ->searchable(),
                
                TextColumn::make('answer_lang')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'it' => 'success',
                        'en' => 'info',
                        'fr' => 'warning',
                        'de' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
            ])
            ->defaultSort('submitdate', 'desc')
            ->paginated([10, 25, 50, 100])
            ->poll('30s');
    }

    protected function getTableQuery()
    {
        $record = $this->getRecord();
        
        if (!$record) {
            return $record->answers()->whereRaw('1 = 0');
        }
        
        return $record->answers()
            ->select(['submitdate', 'answert', 'answer_lang'])
            ->when($record->date_from, function ($query, $dateFrom) {
                $query->where('submitdate', '>=', $dateFrom);
            })
            ->when($record->date_to, function ($query, $dateTo) {
                $query->where('submitdate', '<=', $dateTo);
            });
    }

    protected function getRecord()
    {
        return $this->getTableRecord();
    }
}
```

### 3. **Widget di Grafico (XotBaseWidget)**
```php
<?php

declare(strict_types=1);


use Filament\Widgets\ChartWidget;
use Modules\Xot\Filament\Widgets\XotBaseWidget;

class QuestionChartWidget extends XotBaseWidget
{
    protected static ?string $heading = 'Question Chart Visualization';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 3;

    public function getFormSchema(): array
    {
        return []; // No form needed for chart widget
    }

    protected function getData(): array
    {
        $record = $this->getRecord();
        
        if (!$record) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }
        
        $chartData = $this->getChartData($record);
        
        return [
            'datasets' => [
                [
                    'label' => 'Answer Distribution',
                    'data' => $chartData['values'],
                    'backgroundColor' => $this->getChartColors(count($chartData['values'])),
                    'borderColor' => $this->getChartColors(count($chartData['values']), 0.8),
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $chartData['labels'],
        ];
    }

    protected function getType(): string
    {
        $record = $this->getRecord();
        
        if (!$record) {
            return 'bar';
        }
        
        return match ($record->chart_type) {
            'pie' => 'pie',
            'doughnut' => 'doughnut',
            'line' => 'line',
            default => 'bar',
        };
    }

    protected function getChartData($record): array
    {
        $answers = $record->answers()
            ->select(['answert', 'answer_lang'])
            ->whereNotNull('answert')
            ->when($record->date_from, function ($query, $dateFrom) {
                $query->where('submitdate', '>=', $dateFrom);
            })
            ->when($record->date_to, function ($query, $dateTo) {
                $query->where('submitdate', '<=', $dateTo);
            })
            ->get();
        
        $grouped = $answers->groupBy('answer_lang');
        
        $labels = [];
        $values = [];
        
        foreach ($grouped as $lang => $langAnswers) {
            $labels[] = $lang ?: 'Unknown';
            $values[] = $langAnswers->count();
        }
        
        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }

    protected function getChartColors(int $count, float $alpha = 1.0): array
    {
        $baseColors = [
            'rgba(54, 162, 235, ' . $alpha . ')',
            'rgba(255, 99, 132, ' . $alpha . ')',
            'rgba(255, 205, 86, ' . $alpha . ')',
            'rgba(75, 192, 192, ' . $alpha . ')',
            'rgba(153, 102, 255, ' . $alpha . ')',
            'rgba(255, 159, 64, ' . $alpha . ')',
            'rgba(199, 199, 199, ' . $alpha . ')',
            'rgba(83, 102, 255, ' . $alpha . ')',
        ];
        
        $colors = [];
        for ($i = 0; $i < $count; $i++) {
            $colors[] = $baseColors[$i % count($baseColors)];
        }
        
        return $colors;
    }
}
```

## 🚫 Errori Comuni da Evitare

### 1. **Usare XotBaseWidget per Tabelle**
```php
// ❌ SBAGLIATO
class MyTableWidget extends XotBaseWidget
{
    public function table(Table $table): Table
    {
        // XotBaseWidget richiede getFormSchema()!
    }
}

// ✅ CORRETTO
class MyTableWidget extends XotBaseTableWidget
{
    public function table(Table $table): Table
    {
        // XotBaseTableWidget è per tabelle
    }
}
```

### 2. **Non Implementare Metodi Obbligatori**
```php
// ❌ SBAGLIATO - Manca getFormSchema()
class MyWidget extends XotBaseWidget
{
    // Errore: deve implementare getFormSchema()
}

// ✅ CORRETTO
class MyWidget extends XotBaseWidget
{
    public function getFormSchema(): array
    {
        return [];
    }
}
```

### 3. **Mescolare Responsabilità**
```php
// ❌ SBAGLIATO - Widget che fa tutto
class MyWidget extends XotBaseWidget
{
    public function getFormSchema(): array
    {
        return []; // Form vuoto
    }
    
    public function table(Table $table): Table
    {
        // Tabella in widget di form
    }
}

// ✅ CORRETTO - Separare responsabilità
class MyFilterWidget extends XotBaseWidget
{
    public function getFormSchema(): array
    {
        return [/* form components */];
    }
}

class MyTableWidget extends XotBaseTableWidget
{
    public function table(Table $table): Table
    {
        return $table->columns(/* table columns */);
    }
}
```

## 📋 Checklist Implementazione

### ✅ Prima di Implementare
- [ ] Decidere se widget ha form o tabella
- [ ] Scegliere XotBaseWidget o XotBaseTableWidget
- [ ] Verificare metodi obbligatori da implementare

### ✅ Durante Implementazione
- [ ] Implementare tutti i metodi obbligatori
- [ ] Seguire convenzioni naming
- [ ] Usare type hints corretti
- [ ] Documentare PHPDoc

### ✅ Dopo Implementazione
- [ ] Verificare PHPStan livello 10
- [ ] Controllare conformità PSR-12
- [ ] Testare funzionalità
- [ ] Aggiornare documentazione

## 📚 Riferimenti

- [XotBaseWidget Implementation](./xotbasewidget_implementation.md)
- [XotBaseTableWidget Implementation](./xotbasetablewidget_implementation.md)
- [Laraxot Architecture Rules](./LARAXOT_ARCHITECTURE_RULES.md)

Queste regole garantiscono implementazione corretta dei widget seguendo l'architettura Laraxot.
---

## widget-implementation-widget-implementation-rules-xot-module

*Consolidated from: `widget-implementation-widget-implementation-rules-xot-module.md`*


## 🎯 Regole Fondamentali per Widget

### 1. **Tipi di Widget Disponibili**

#### **XotBaseWidget** - Per widget con form
```php
// ✅ CORRETTO - Widget con form
class MyFormWidget extends XotBaseWidget
{
    public function getFormSchema(): array
    {
        return [
            // Form components
        ];
    }
}
```

#### **XotBaseTableWidget** - Per widget di tabella
```php
// ✅ CORRETTO - Widget di tabella
class MyTableWidget extends XotBaseTableWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns($this->getTableColumns());
    }
}
```

### 2. **MAI mescolare i tipi**
```php
// ❌ SBAGLIATO - XotBaseWidget per tabella
class MyTableWidget extends XotBaseWidget
{
    public function table(Table $table): Table
    {
        // XotBaseWidget richiede getFormSchema()!
    }
}

// ✅ CORRETTO - XotBaseTableWidget per tabella
class MyTableWidget extends XotBaseTableWidget
{
    public function table(Table $table): Table
    {
        // XotBaseTableWidget è progettato per tabelle
    }
}
```

### 3. **Metodi Obbligatori per Tipo**

#### **XotBaseWidget**
- `getFormSchema(): array` - **OBBLIGATORIO**

#### **XotBaseTableWidget**
- `table(Table $table): Table` - **OBBLIGATORIO**
- `getTableQuery()` - **OBBLIGATORIO**
- `getTableColumns(): array` - **OBBLIGATORIO**

## 🏗️ Implementazione Corretta

### 1. **Widget con Filtri (XotBaseWidget)**
```php
<?php

declare(strict_types=1);

namespace Modules\<nome progetto>\Filament\Widgets;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Modules\Xot\Filament\Widgets\XotBaseWidget;

class QuestionChartFilterWidget extends XotBaseWidget
{
    protected static ?string $heading = 'Filter Options';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 1;

    public ?string $dateFrom = null;
    public ?string $dateTo = null;
    public ?string $answerFilter = null;

    public function getFormSchema(): array
    {
        return [
            DatePicker::make('dateFrom')
                ->live()
                ->afterStateUpdated(fn () => $this->updateFilters()),

            DatePicker::make('dateTo')
                ->live()
                ->afterStateUpdated(fn () => $this->updateFilters()),

            Select::make('answerFilter')
                ->options([
                    'all' => 'All Answers',
                    'answered' => 'Answered Only',
                    'not_answered' => 'Not Answered',
                ])
                ->default('all')
                ->live()
                ->afterStateUpdated(fn () => $this->updateFilters()),
        ];
    }

    public function updateFilters(): void
    {
        $this->dispatch('filters-updated', [
            'dateFrom' => $this->dateFrom,
            'dateTo' => $this->dateTo,
            'answerFilter' => $this->answerFilter,
        ]);
    }
}
```

### 2. **Widget di Tabella (XotBaseTableWidget)**
```php
<?php

declare(strict_types=1);

namespace Modules\<nome progetto>\Filament\Widgets;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Xot\Filament\Widgets\XotBaseTableWidget;

class QuestionChartDataWidget extends XotBaseTableWidget
{
    protected static ?string $heading = 'Question Answer Data';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('submitdate')
                    ->dateTime()
                    ->sortable()
                    ->searchable(),

                TextColumn::make('answert')
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 50 ? $state : null;
                    })
                    ->searchable(),

                TextColumn::make('answer_lang')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'it' => 'success',
                        'en' => 'info',
                        'fr' => 'warning',
                        'de' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
            ])
            ->defaultSort('submitdate', 'desc')
            ->paginated([10, 25, 50, 100])
            ->poll('30s');
    }

    protected function getTableQuery()
    {
        $record = $this->getRecord();

        if (!$record) {
            return $record->answers()->whereRaw('1 = 0');
        }

        return $record->answers()
            ->select(['submitdate', 'answert', 'answer_lang'])
            ->when($record->date_from, function ($query, $dateFrom) {
                $query->where('submitdate', '>=', $dateFrom);
            })
            ->when($record->date_to, function ($query, $dateTo) {
                $query->where('submitdate', '<=', $dateTo);
            });
    }

    protected function getRecord()
    {
        return $this->getTableRecord();
    }
}
```

### 3. **Widget di Grafico (XotBaseWidget)**
```php
<?php

declare(strict_types=1);

namespace Modules\<nome progetto>\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Modules\Xot\Filament\Widgets\XotBaseWidget;

class QuestionChartWidget extends XotBaseWidget
{
    protected static ?string $heading = 'Question Chart Visualization';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 3;

    public function getFormSchema(): array
    {
        return []; // No form needed for chart widget
    }

    protected function getData(): array
    {
        $record = $this->getRecord();

        if (!$record) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        $chartData = $this->getChartData($record);

        return [
            'datasets' => [
                [
                    'label' => 'Answer Distribution',
                    'data' => $chartData['values'],
                    'backgroundColor' => $this->getChartColors(count($chartData['values'])),
                    'borderColor' => $this->getChartColors(count($chartData['values']), 0.8),
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $chartData['labels'],
        ];
    }

    protected function getType(): string
    {
        $record = $this->getRecord();

        if (!$record) {
            return 'bar';
        }

        return match ($record->chart_type) {
            'pie' => 'pie',
            'doughnut' => 'doughnut',
            'line' => 'line',
            default => 'bar',
        };
    }

    protected function getChartData($record): array
    {
        $answers = $record->answers()
            ->select(['answert', 'answer_lang'])
            ->whereNotNull('answert')
            ->when($record->date_from, function ($query, $dateFrom) {
                $query->where('submitdate', '>=', $dateFrom);
            })
            ->when($record->date_to, function ($query, $dateTo) {
                $query->where('submitdate', '<=', $dateTo);
            })
            ->get();

        $grouped = $answers->groupBy('answer_lang');

        $labels = [];
        $values = [];

        foreach ($grouped as $lang => $langAnswers) {
            $labels[] = $lang ?: 'Unknown';
            $values[] = $langAnswers->count();
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }

    protected function getChartColors(int $count, float $alpha = 1.0): array
    {
        $baseColors = [
            'rgba(54, 162, 235, ' . $alpha . ')',
            'rgba(255, 99, 132, ' . $alpha . ')',
            'rgba(255, 205, 86, ' . $alpha . ')',
            'rgba(75, 192, 192, ' . $alpha . ')',
            'rgba(153, 102, 255, ' . $alpha . ')',
            'rgba(255, 159, 64, ' . $alpha . ')',
            'rgba(199, 199, 199, ' . $alpha . ')',
            'rgba(83, 102, 255, ' . $alpha . ')',
        ];

        $colors = [];
        for ($i = 0; $i < $count; $i++) {
            $colors[] = $baseColors[$i % count($baseColors)];
        }

        return $colors;
    }
}
```

## 🚫 Errori Comuni da Evitare

### 1. **Usare XotBaseWidget per Tabelle**
```php
// ❌ SBAGLIATO
class MyTableWidget extends XotBaseWidget
{
    public function table(Table $table): Table
    {
        // XotBaseWidget richiede getFormSchema()!
    }
}

// ✅ CORRETTO
class MyTableWidget extends XotBaseTableWidget
{
    public function table(Table $table): Table
    {
        // XotBaseTableWidget è per tabelle
    }
}
```

### 2. **Non Implementare Metodi Obbligatori**
```php
// ❌ SBAGLIATO - Manca getFormSchema()
class MyWidget extends XotBaseWidget
{
    // Errore: deve implementare getFormSchema()
}

// ✅ CORRETTO
class MyWidget extends XotBaseWidget
{
    public function getFormSchema(): array
    {
        return [];
    }
}
```

### 3. **Mescolare Responsabilità**
```php
// ❌ SBAGLIATO - Widget che fa tutto
class MyWidget extends XotBaseWidget
{
    public function getFormSchema(): array
    {
        return []; // Form vuoto
    }

    public function table(Table $table): Table
    {
        // Tabella in widget di form
    }
}

// ✅ CORRETTO - Separare responsabilità
class MyFilterWidget extends XotBaseWidget
{
    public function getFormSchema(): array
    {
        return [/* form components */];
    }
}

class MyTableWidget extends XotBaseTableWidget
{
    public function table(Table $table): Table
    {
        return $table->columns(/* table columns */);
    }
}
```

## 📋 Checklist Implementazione

### ✅ Prima di Implementare
- [ ] Decidere se widget ha form o tabella
- [ ] Scegliere XotBaseWidget o XotBaseTableWidget
- [ ] Verificare metodi obbligatori da implementare

### ✅ Durante Implementazione
- [ ] Implementare tutti i metodi obbligatori
- [ ] Seguire convenzioni naming
- [ ] Usare type hints corretti
- [ ] Documentare PHPDoc

### ✅ Dopo Implementazione
- [ ] Verificare PHPStan livello 10
- [ ] Controllare conformità PSR-12
- [ ] Testare funzionalità
- [ ] Aggiornare documentazione

## 📚 Riferimenti

- [XotBaseWidget Implementation](./xotbasewidget_implementation.md)
- [XotBaseTableWidget Implementation](./xotbasetablewidget_implementation.md)
- [Laraxot Architecture Rules](./laraxot_architecture_rules.md)

Queste regole garantiscono implementazione corretta dei widget seguendo l'architettura Laraxot.

---

## widget-implementation

*Consolidated from: `widget-implementation.md`*


## 🎯 Regole Fondamentali per Widget

### 1. **Tipi di Widget Disponibili**

#### **XotBaseWidget** - Per widget con form
```php
// ✅ CORRETTO - Widget con form
class MyFormWidget extends XotBaseWidget
{
    public function getFormSchema(): array
    {
        return [
            // Form components
        ];
    }
}
```

#### **XotBaseTableWidget** - Per widget di tabella
```php
// ✅ CORRETTO - Widget di tabella
class MyTableWidget extends XotBaseTableWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns($this->getTableColumns());
    }
}
```

### 2. **MAI mescolare i tipi**
```php
// ❌ SBAGLIATO - XotBaseWidget per tabella
class MyTableWidget extends XotBaseWidget
{
    public function table(Table $table): Table
    {
        // XotBaseWidget richiede getFormSchema()!
    }
}

// ✅ CORRETTO - XotBaseTableWidget per tabella
class MyTableWidget extends XotBaseTableWidget
{
    public function table(Table $table): Table
    {
        // XotBaseTableWidget è progettato per tabelle
    }
}
```

### 3. **Metodi Obbligatori per Tipo**

#### **XotBaseWidget**
- `getFormSchema(): array` - **OBBLIGATORIO**

#### **XotBaseTableWidget**
- `table(Table $table): Table` - **OBBLIGATORIO**
- `getTableQuery()` - **OBBLIGATORIO**
- `getTableColumns(): array` - **OBBLIGATORIO**

## 🏗️ Implementazione Corretta

### 1. **Widget con Filtri (XotBaseWidget)**
```php
<?php

declare(strict_types=1);

namespace Modules\<nome progetto>\Filament\Widgets;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Modules\Xot\Filament\Widgets\XotBaseWidget;

class QuestionChartFilterWidget extends XotBaseWidget
{
    protected static ?string $heading = 'Filter Options';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 1;

    public ?string $dateFrom = null;
    public ?string $dateTo = null;
    public ?string $answerFilter = null;

    public function getFormSchema(): array
    {
        return [
            DatePicker::make('dateFrom')
                ->live()
                ->afterStateUpdated(fn () => $this->updateFilters()),

            DatePicker::make('dateTo')
                ->live()
                ->afterStateUpdated(fn () => $this->updateFilters()),

            Select::make('answerFilter')
                ->options([
                    'all' => 'All Answers',
                    'answered' => 'Answered Only',
                    'not_answered' => 'Not Answered',
                ])
                ->default('all')
                ->live()
                ->afterStateUpdated(fn () => $this->updateFilters()),
        ];
    }

    public function updateFilters(): void
    {
        $this->dispatch('filters-updated', [
            'dateFrom' => $this->dateFrom,
            'dateTo' => $this->dateTo,
            'answerFilter' => $this->answerFilter,
        ]);
    }
}
```

### 2. **Widget di Tabella (XotBaseTableWidget)**
```php
<?php

declare(strict_types=1);

namespace Modules\<nome progetto>\Filament\Widgets;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Xot\Filament\Widgets\XotBaseTableWidget;

class QuestionChartDataWidget extends XotBaseTableWidget
{
    protected static ?string $heading = 'Question Answer Data';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('submitdate')
                    ->dateTime()
                    ->sortable()
                    ->searchable(),

                TextColumn::make('answert')
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 50 ? $state : null;
                    })
                    ->searchable(),

                TextColumn::make('answer_lang')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'it' => 'success',
                        'en' => 'info',
                        'fr' => 'warning',
                        'de' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
            ])
            ->defaultSort('submitdate', 'desc')
            ->paginated([10, 25, 50, 100])
            ->poll('30s');
    }

    protected function getTableQuery()
    {
        $record = $this->getRecord();

        if (!$record) {
            return $record->answers()->whereRaw('1 = 0');
        }

        return $record->answers()
            ->select(['submitdate', 'answert', 'answer_lang'])
            ->when($record->date_from, function ($query, $dateFrom) {
                $query->where('submitdate', '>=', $dateFrom);
            })
            ->when($record->date_to, function ($query, $dateTo) {
                $query->where('submitdate', '<=', $dateTo);
            });
    }

    protected function getRecord()
    {
        return $this->getTableRecord();
    }
}
```

### 3. **Widget di Grafico (XotBaseWidget)**
```php
<?php

declare(strict_types=1);

namespace Modules\<nome progetto>\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Modules\Xot\Filament\Widgets\XotBaseWidget;

class QuestionChartWidget extends XotBaseWidget
{
    protected static ?string $heading = 'Question Chart Visualization';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 3;

    public function getFormSchema(): array
    {
        return []; // No form needed for chart widget
    }

    protected function getData(): array
    {
        $record = $this->getRecord();

        if (!$record) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        $chartData = $this->getChartData($record);

        return [
            'datasets' => [
                [
                    'label' => 'Answer Distribution',
                    'data' => $chartData['values'],
                    'backgroundColor' => $this->getChartColors(count($chartData['values'])),
                    'borderColor' => $this->getChartColors(count($chartData['values']), 0.8),
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $chartData['labels'],
        ];
    }

    protected function getType(): string
    {
        $record = $this->getRecord();

        if (!$record) {
            return 'bar';
        }

        return match ($record->chart_type) {
            'pie' => 'pie',
            'doughnut' => 'doughnut',
            'line' => 'line',
            default => 'bar',
        };
    }

    protected function getChartData($record): array
    {
        $answers = $record->answers()
            ->select(['answert', 'answer_lang'])
            ->whereNotNull('answert')
            ->when($record->date_from, function ($query, $dateFrom) {
                $query->where('submitdate', '>=', $dateFrom);
            })
            ->when($record->date_to, function ($query, $dateTo) {
                $query->where('submitdate', '<=', $dateTo);
            })
            ->get();

        $grouped = $answers->groupBy('answer_lang');

        $labels = [];
        $values = [];

        foreach ($grouped as $lang => $langAnswers) {
            $labels[] = $lang ?: 'Unknown';
            $values[] = $langAnswers->count();
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }

    protected function getChartColors(int $count, float $alpha = 1.0): array
    {
        $baseColors = [
            'rgba(54, 162, 235, ' . $alpha . ')',
            'rgba(255, 99, 132, ' . $alpha . ')',
            'rgba(255, 205, 86, ' . $alpha . ')',
            'rgba(75, 192, 192, ' . $alpha . ')',
            'rgba(153, 102, 255, ' . $alpha . ')',
            'rgba(255, 159, 64, ' . $alpha . ')',
            'rgba(199, 199, 199, ' . $alpha . ')',
            'rgba(83, 102, 255, ' . $alpha . ')',
        ];

        $colors = [];
        for ($i = 0; $i < $count; $i++) {
            $colors[] = $baseColors[$i % count($baseColors)];
        }

        return $colors;
    }
}
```

## 🚫 Errori Comuni da Evitare

### 1. **Usare XotBaseWidget per Tabelle**
```php
// ❌ SBAGLIATO
class MyTableWidget extends XotBaseWidget
{
    public function table(Table $table): Table
    {
        // XotBaseWidget richiede getFormSchema()!
    }
}

// ✅ CORRETTO
class MyTableWidget extends XotBaseTableWidget
{
    public function table(Table $table): Table
    {
        // XotBaseTableWidget è per tabelle
    }
}
```

### 2. **Non Implementare Metodi Obbligatori**
```php
// ❌ SBAGLIATO - Manca getFormSchema()
class MyWidget extends XotBaseWidget
{
    // Errore: deve implementare getFormSchema()
}

// ✅ CORRETTO
class MyWidget extends XotBaseWidget
{
    public function getFormSchema(): array
    {
        return [];
    }
}
```

### 3. **Mescolare Responsabilità**
```php
// ❌ SBAGLIATO - Widget che fa tutto
class MyWidget extends XotBaseWidget
{
    public function getFormSchema(): array
    {
        return []; // Form vuoto
    }

    public function table(Table $table): Table
    {
        // Tabella in widget di form
    }
}

// ✅ CORRETTO - Separare responsabilità
class MyFilterWidget extends XotBaseWidget
{
    public function getFormSchema(): array
    {
        return [/* form components */];
    }
}

class MyTableWidget extends XotBaseTableWidget
{
    public function table(Table $table): Table
    {
        return $table->columns(/* table columns */);
    }
}
```

## 📋 Checklist Implementazione

### ✅ Prima di Implementare
- [ ] Decidere se widget ha form o tabella
- [ ] Scegliere XotBaseWidget o XotBaseTableWidget
- [ ] Verificare metodi obbligatori da implementare

### ✅ Durante Implementazione
- [ ] Implementare tutti i metodi obbligatori
- [ ] Seguire convenzioni naming
- [ ] Usare type hints corretti
- [ ] Documentare PHPDoc

### ✅ Dopo Implementazione
- [ ] Verificare PHPStan livello 10
- [ ] Controllare conformità PSR-12
- [ ] Testare funzionalità
- [ ] Aggiornare documentazione

## 📚 Riferimenti

- [XotBaseWidget Implementation](./xotbasewidget_implementation.md)
- [XotBaseTableWidget Implementation](./xotbasetablewidget_implementation.md)
- [Laraxot Architecture Rules](./laraxot_architecture_rules.md)

Queste regole garantiscono implementazione corretta dei widget seguendo l'architettura Laraxot.
# Widget Implementation Rules - Xot Module

## 🎯 Regole Fondamentali per Widget

### 1. **Tipi di Widget Disponibili**

#### **XotBaseWidget** - Per widget con form
```php
// ✅ CORRETTO - Widget con form
class MyFormWidget extends XotBaseWidget
{
    public function getFormSchema(): array
    {
        return [
            // Form components
        ];
    }
}
```

#### **XotBaseTableWidget** - Per widget di tabella
```php
// ✅ CORRETTO - Widget di tabella
class MyTableWidget extends XotBaseTableWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns($this->getTableColumns());
    }
}
```

### 2. **MAI mescolare i tipi**
```php
// ❌ SBAGLIATO - XotBaseWidget per tabella
class MyTableWidget extends XotBaseWidget
{
    public function table(Table $table): Table
    {
        // XotBaseWidget richiede getFormSchema()!
    }
}

// ✅ CORRETTO - XotBaseTableWidget per tabella
class MyTableWidget extends XotBaseTableWidget
{
    public function table(Table $table): Table
    {
        // XotBaseTableWidget è progettato per tabelle
    }
}
```

### 3. **Metodi Obbligatori per Tipo**

#### **XotBaseWidget**
- `getFormSchema(): array` - **OBBLIGATORIO**

#### **XotBaseTableWidget**
- `table(Table $table): Table` - **OBBLIGATORIO**
- `getTableQuery()` - **OBBLIGATORIO**
- `getTableColumns(): array` - **OBBLIGATORIO**

## 🏗️ Implementazione Corretta

### 1. **Widget con Filtri (XotBaseWidget)**
```php
<?php

declare(strict_types=1);

namespace Modules\healthcare_app\Filament\Widgets;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Modules\Xot\Filament\Widgets\XotBaseWidget;

class QuestionChartFilterWidget extends XotBaseWidget
{
    protected static ?string $heading = 'Filter Options';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 1;

    public ?string $dateFrom = null;
    public ?string $dateTo = null;
    public ?string $answerFilter = null;

    public function getFormSchema(): array
    {
        return [
            DatePicker::make('dateFrom')
                ->live()
                ->afterStateUpdated(fn () => $this->updateFilters()),

            DatePicker::make('dateTo')
                ->live()
                ->afterStateUpdated(fn () => $this->updateFilters()),

            Select::make('answerFilter')
                ->options([
                    'all' => 'All Answers',
                    'answered' => 'Answered Only',
                    'not_answered' => 'Not Answered',
                ])
                ->default('all')
                ->live()
                ->afterStateUpdated(fn () => $this->updateFilters()),
        ];
    }

    public function updateFilters(): void
    {
        $this->dispatch('filters-updated', [
            'dateFrom' => $this->dateFrom,
            'dateTo' => $this->dateTo,
            'answerFilter' => $this->answerFilter,
        ]);
    }
}
```

### 2. **Widget di Tabella (XotBaseTableWidget)**
```php
<?php

declare(strict_types=1);

namespace Modules\healthcare_app\Filament\Widgets;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Xot\Filament\Widgets\XotBaseTableWidget;

class QuestionChartDataWidget extends XotBaseTableWidget
{
    protected static ?string $heading = 'Question Answer Data';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('submitdate')
                    ->dateTime()
                    ->sortable()
                    ->searchable(),

                TextColumn::make('answert')
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 50 ? $state : null;
                    })
                    ->searchable(),

                TextColumn::make('answer_lang')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'it' => 'success',
                        'en' => 'info',
                        'fr' => 'warning',
                        'de' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
            ])
            ->defaultSort('submitdate', 'desc')
            ->paginated([10, 25, 50, 100])
            ->poll('30s');
    }

    protected function getTableQuery()
    {
        $record = $this->getRecord();

        if (!$record) {
            return $record->answers()->whereRaw('1 = 0');
        }

        return $record->answers()
            ->select(['submitdate', 'answert', 'answer_lang'])
            ->when($record->date_from, function ($query, $dateFrom) {
                $query->where('submitdate', '>=', $dateFrom);
            })
            ->when($record->date_to, function ($query, $dateTo) {
                $query->where('submitdate', '<=', $dateTo);
            });
    }

    protected function getRecord()
    {
        return $this->getTableRecord();
    }
}
```

### 3. **Widget di Grafico (XotBaseWidget)**
```php
<?php

declare(strict_types=1);

namespace Modules\healthcare_app\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Modules\Xot\Filament\Widgets\XotBaseWidget;

class QuestionChartWidget extends XotBaseWidget
{
    protected static ?string $heading = 'Question Chart Visualization';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 3;

    public function getFormSchema(): array
    {
        return []; // No form needed for chart widget
    }

    protected function getData(): array
    {
        $record = $this->getRecord();

        if (!$record) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        $chartData = $this->getChartData($record);

        return [
            'datasets' => [
                [
                    'label' => 'Answer Distribution',
                    'data' => $chartData['values'],
                    'backgroundColor' => $this->getChartColors(count($chartData['values'])),
                    'borderColor' => $this->getChartColors(count($chartData['values']), 0.8),
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $chartData['labels'],
        ];
    }

    protected function getType(): string
    {
        $record = $this->getRecord();

        if (!$record) {
            return 'bar';
        }

        return match ($record->chart_type) {
            'pie' => 'pie',
            'doughnut' => 'doughnut',
            'line' => 'line',
            default => 'bar',
        };
    }

    protected function getChartData($record): array
    {
        $answers = $record->answers()
            ->select(['answert', 'answer_lang'])
            ->whereNotNull('answert')
            ->when($record->date_from, function ($query, $dateFrom) {
                $query->where('submitdate', '>=', $dateFrom);
            })
            ->when($record->date_to, function ($query, $dateTo) {
                $query->where('submitdate', '<=', $dateTo);
            })
            ->get();

        $grouped = $answers->groupBy('answer_lang');

        $labels = [];
        $values = [];

        foreach ($grouped as $lang => $langAnswers) {
            $labels[] = $lang ?: 'Unknown';
            $values[] = $langAnswers->count();
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }

    protected function getChartColors(int $count, float $alpha = 1.0): array
    {
        $baseColors = [
            'rgba(54, 162, 235, ' . $alpha . ')',
            'rgba(255, 99, 132, ' . $alpha . ')',
            'rgba(255, 205, 86, ' . $alpha . ')',
            'rgba(75, 192, 192, ' . $alpha . ')',
            'rgba(153, 102, 255, ' . $alpha . ')',
            'rgba(255, 159, 64, ' . $alpha . ')',
            'rgba(199, 199, 199, ' . $alpha . ')',
            'rgba(83, 102, 255, ' . $alpha . ')',
        ];

        $colors = [];
        for ($i = 0; $i < $count; $i++) {
            $colors[] = $baseColors[$i % count($baseColors)];
        }

        return $colors;
    }
}
```

## 🚫 Errori Comuni da Evitare

### 1. **Usare XotBaseWidget per Tabelle**
```php
// ❌ SBAGLIATO
class MyTableWidget extends XotBaseWidget
{
    public function table(Table $table): Table
    {
        // XotBaseWidget richiede getFormSchema()!
    }
}

// ✅ CORRETTO
class MyTableWidget extends XotBaseTableWidget
{
    public function table(Table $table): Table
    {
        // XotBaseTableWidget è per tabelle
    }
}
```

### 2. **Non Implementare Metodi Obbligatori**
```php
// ❌ SBAGLIATO - Manca getFormSchema()
class MyWidget extends XotBaseWidget
{
    // Errore: deve implementare getFormSchema()
}

// ✅ CORRETTO
class MyWidget extends XotBaseWidget
{
    public function getFormSchema(): array
    {
        return [];
    }
}
```

### 3. **Mescolare Responsabilità**
```php
// ❌ SBAGLIATO - Widget che fa tutto
class MyWidget extends XotBaseWidget
{
    public function getFormSchema(): array
    {
        return []; // Form vuoto
    }

    public function table(Table $table): Table
    {
        // Tabella in widget di form
    }
}

// ✅ CORRETTO - Separare responsabilità
class MyFilterWidget extends XotBaseWidget
{
    public function getFormSchema(): array
    {
        return [/* form components */];
    }
}

class MyTableWidget extends XotBaseTableWidget
{
    public function table(Table $table): Table
    {
        return $table->columns(/* table columns */);
    }
}
```

## 📋 Checklist Implementazione

### ✅ Prima di Implementare
- [ ] Decidere se widget ha form o tabella
- [ ] Scegliere XotBaseWidget o XotBaseTableWidget
- [ ] Verificare metodi obbligatori da implementare

### ✅ Durante Implementazione
- [ ] Implementare tutti i metodi obbligatori
- [ ] Seguire convenzioni naming
- [ ] Usare type hints corretti
- [ ] Documentare PHPDoc

### ✅ Dopo Implementazione
- [ ] Verificare PHPStan livello 10
- [ ] Controllare conformità PSR-12
- [ ] Testare funzionalità
- [ ] Aggiornare documentazione

## 📚 Riferimenti

- [XotBaseWidget Implementation](./xotbasewidget_implementation.md)
- [XotBaseTableWidget Implementation](./xotbasetablewidget_implementation.md)
- [Laraxot Architecture Rules](./laraxot_architecture_rules.md)

Queste regole garantiscono implementazione corretta dei widget seguendo l'architettura Laraxot.
---

## widget-view-resolution

*Consolidated from: `widget-view-resolution.md`*


**Data**: 2025-01-27
**Status**: ✅ RISOLTO
**Problema**: `XotBaseWidget` sovrascriveva view definite manualmente

---

## 🔍 Problema Identificato

### Sintomo
```
View not found: pub_theme::filament.widgets.timeclock, employee::filament.widgets.timeclock
```

### Causa Root

Il widget `TimeClockWidget` definiva manualmente la view:
```php
protected string $view = 'employee::filament.widgets.time-clock-widget';
```

Ma il costruttore di `XotBaseWidget` cercava automaticamente la view basandosi sul nome della classe:
- Classe: `Modules\Employee\Filament\Widgets\TimeClockWidget`
- View cercata: `employee::filament.widgets.timeclock` (tutto minuscolo, senza trattino)
- View esistente: `employee::filament.widgets.time-clock-widget` (con trattino)

Il sistema lanciava un'eccezione perché la view automatica non esisteva, anche se la view manuale era corretta.

---

## ✅ Soluzione Implementata

### Modifica a `XotBaseWidget`

Il costruttore ora controlla se la view è già definita manualmente prima di cercarla automaticamente:

```php
public function __construct()
{
    // Se la view è già definita manualmente e diversa dal default, non cercarla automaticamente
    $defaultView = 'xot::filament.widgets.base';
    if ($this->view !== $defaultView && view()->exists($this->view)) {
        // View già definita manualmente, usala
        return;
    }

    // Cerca automaticamente la view basandosi sul nome della classe
    try {
        $view = app(GetViewByClassAction::class)->execute(static::class);
        if (view()->exists($view)) {
            $this->view = $view;
        }
    } catch (\Exception $e) {
        // Se la view automatica non esiste, mantieni quella definita manualmente o il default
        // Non lanciare eccezione se la view è già definita manualmente
        if ($this->view === $defaultView) {
            throw $e;
        }
    }
}
```

### Comportamento

1. **View definita manualmente**: Se il widget definisce una view diversa dal default e la view esiste, viene usata quella (non viene cercata automaticamente)
2. **View automatica**: Se la view non è definita manualmente, viene cercata automaticamente basandosi sul nome della classe
3. **Fallback**: Se la view automatica non esiste ma la view manuale è definita, non viene lanciata eccezione

---

## 📋 Pattern di Utilizzo

### Pattern 1: View Manuale (Raccomandato per nomi complessi)

```php
class TimeClockWidget extends XotBaseWidget
{
    protected string $view = 'employee::filament.widgets.time-clock-widget';

    public function getFormSchema(): array
    {
        return [];
    }
}
```

**Quando usare**:
- Nome widget complesso con trattini
- View con nome diverso dal pattern automatico
- Controllo esplicito sulla view utilizzata

### Pattern 2: View Automatica (Default)

```php
class SimpleWidget extends XotBaseWidget
{
    // Non definire $view - viene cercata automaticamente
    // Pattern: {modulo}::filament.widgets.{nome-classe-slug}
    // Esempio: employee::filament.widgets.simple-widget

    public function getFormSchema(): array
    {
        return [];
    }
}
```

**Quando usare**:
- Nome widget semplice che segue il pattern automatico
- Convenzione naming standard

---

## 🔗 Pattern di Naming View Automatico

Il sistema `GetViewByClassAction` converte il nome della classe in view seguendo questo pattern:

```
Modules\{Module}\Filament\Widgets\{WidgetName}
↓
{module-lowercase}::filament.widgets.{widget-name-slug}
```

**Esempi**:
- `Modules\Employee\Filament\Widgets\TimeClockWidget` → `employee::filament.widgets.timeclock`
- `Modules\Employee\Filament\Widgets\SimpleWidget` → `employee::filament.widgets.simple-widget`
- `Modules\UI\Filament\Widgets\GroupWidget` → `ui::filament.widgets.group`

**Nota**: Il sistema cerca anche `pub_theme::` come fallback prima del modulo.

---

## 🧪 Test Case

### Test 1: View Manuale Esistente
```php
class MyWidget extends XotBaseWidget
{
    protected string $view = 'employee::filament.widgets.my-custom-view';
    // ✅ Usa la view manuale, non cerca automaticamente
}
```

### Test 2: View Automatica
```php
class MyWidget extends XotBaseWidget
{
    // ✅ Cerca automaticamente: employee::filament.widgets.my-widget
}
```

### Test 3: View Manuale Non Esistente
```php
class MyWidget extends XotBaseWidget
{
    protected string $view = 'employee::filament.widgets.non-existent';
    // ⚠️ View non esiste, ma non viene cercata automaticamente
    // Il sistema userà questa view e fallirà al rendering
}
```

---

## 📝 Best Practices

1. **Definire sempre la view manualmente** se il nome widget è complesso o contiene trattini
2. **Verificare che la view esista** prima di definirla manualmente
3. **Usare naming consistente**: se possibile, seguire il pattern automatico
4. **Documentare view custom** nel widget se il nome non è ovvio

---

## 🔗 Collegamenti

- [XotBaseWidget.php](../../app/Filament/Widgets/XotBaseWidget.php) - Implementazione
- [GetViewByClassAction.php](../../app/Actions/View/GetViewByClassAction.php) - Logica risoluzione automatica
- [Widgets Initialization](./widgets-initialization.md) - Documentazione inizializzazione widget

---

*Documento creato il 2025-01-27 durante la risoluzione del bug "View not found: timeclock"*

---

## widgets-initialization

*Consolidated from: `widgets-initialization.md`*


## ⚠️ Problema delle Signature (Incompatibilità)

In Filament v4 (Livewire 3), i widget che estendono `XotBaseWidget` (che a sua volta estende `Filament\Widgets\Widget`) spesso presentano firme (signature) del metodo `mount()` differenti:

- `EditUserWidget::mount(string $type, ?string $userId = null)`
- `TimeClockWidget::mount()` (senza parametri)
- Altri widget potrebbero avere parametri diversi passati da layout o pagine.

Se `XotBaseWidget` definisce un metodo `mount()`, tutti i figli devono avere una firma compatibile. Dato che il progetto ha oltre 120 widget, è impossibile e rischioso uniformarli tutti o usare firme variadiche che potrebbero nascondere errori.

## 🚀 Soluzione: Inizializzazione nel Metodo `form()`

Per mantenere il principio **DRY** (Don't Repeat Yourself) e garantire che il form sia correttamente inizializzato (soprattutto con `statePath('data')`), l'inizializzazione di `$this->data` avviene direttamente nel metodo `form()` invece che in `mount()`.

### 1. Definizione in `XotBaseWidget::form()`

L'inizializzazione di `$this->data` avviene automaticamente nel metodo `form()`:

```php
public function form(Schema $schema): Schema
{
    $schema = $schema->components($this->getFormSchema());
    $schema->statePath('data');
    $data = $this->getFormFill();

    // Per widget senza modello, inizializza $this->data con le chiavi dello schema
    // per garantire che Livewire possa correttamente bindare i campi con statePath('data')
    if (empty($data)) {
        $schemaKeys = array_keys($this->getFormSchema());
        $data = array_fill_keys($schemaKeys, null);
    }

    $this->data = $data;

    $model = $this->getFormModel();
    if ($model !== null) {
        // Configurazione modello...
    }

    return $schema;
}
```

### 2. Pattern per Widget senza Modello

I widget senza modello (come `LoginWidget`) **NON devono** implementare `mount()`:

```php
// ✅ CORRETTO: Nessun mount() necessario
class LoginWidget extends XotBaseWidget
{
    #[\Override]
    public function getFormSchema(): array
    {
        return [
            'email' => TextInput::make('email')->email()->required(),
            'password' => TextInput::make('password')->password()->required(),
            'remember' => Checkbox::make('remember'),
        ];
    }

    // mount() NON necessario - l'inizializzazione avviene in form()
}
```

### 3. Pattern per Widget con Modello o Logica Aggiuntiva

I widget che hanno bisogno di logica aggiuntiva nel `mount()` (es. caricare dati dal database) possono implementare `mount()`:

```php
// ✅ CORRETTO: mount() con logica aggiuntiva
class EditUserWidget extends XotBaseWidget
{
    public function mount(string $type, ?string $userId = null): void
    {
        // Logica specifica (carica record, setta proprietà, ecc.)
        $this->type = $type;
        $this->record = $this->getFormModel($userId);

        // NON serve chiamare initXotBaseWidget() - form() gestisce tutto
    }
}
```

## 🔒 Perché è importante?

Senza l'inizializzazione di `$this->data` con le chiavi dello schema:
1.  **Livewire non trova le proprietà**: Quando si usa `statePath('data')`, Livewire cerca le chiavi in `$this->data`
2.  **Errore "property does not exist"**: Se `$this->data = []`, Livewire non può bindare `wire:model="data.email"` perché la chiave `email` non esiste
3.  **Form non funziona**: I campi non vengono popolati o validati correttamente

Questo problema è stato diagnosticato e risolto durante il debug del `LoginWidget`, che mostrava errori:
```
Livewire: [wire:model="email"] property does not exist on component
```

## 🧪 Casi Particolari

### LoginWidget (Widget senza Modello)

**NON** implementa `mount()` - l'inizializzazione avviene automaticamente in `form()`:

```php
class LoginWidget extends XotBaseWidget
{
    #[\Override]
    public function getFormSchema(): array
    {
        return [
            'email' => TextInput::make('email')->email()->required(),
            'password' => TextInput::make('password')->password()->required(),
            'remember' => Checkbox::make('remember'),
        ];
    }

    // mount() NON necessario
}
```

### EditUserWidget (Widget con Modello e Parametri)

Implementa `mount()` per gestire parametri, ma **NON** deve inizializzare il form manualmente:

```php
class EditUserWidget extends XotBaseWidget
{
    public function mount(string $type, ?string $userId = null): void
    {
        // Solo logica specifica - NON inizializzare $this->data qui
        $this->type = $type;
        $this->record = $this->getFormModel($userId);

        // form() gestirà automaticamente l'inizializzazione di $this->data
    }
}
```

### EnvWidget (Widget con Dati Custom)

Widget che necessita di caricare dati da fonti esterne nel `mount()`:

```php
class EnvWidget extends XotBaseWidget
{
    public function mount(): void
    {
        // Carica dati da EnvData
        $data = EnvData::make()->toArray();
        $this->data = $data;
        $this->form->fill($this->data);
    }
}
```

**NOTA**: Questo pattern è valido solo se il widget NON estende `XotBaseWidget` o se ha esigenze speciali. Per la maggior parte dei widget, l'inizializzazione automatica in `form()` è sufficiente.

---

## widgetsization

*Consolidated from: `widgetsization.md`*

module: theme
topic: widgetsization
canonical: ../../../Themes/docs/shared-components/widgets-initialization.md
---

See canonical documentation: ../../../Themes/docs/shared-components/widgets-initialization.md
---

**Consolidated by:** Phase 2f intelligent merging
**Date:** 2026-08-04
