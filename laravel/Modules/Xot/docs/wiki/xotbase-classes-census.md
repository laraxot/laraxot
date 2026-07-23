---
title: "XotBase Classes Census"
type: census
tags: [xotbase, architecture, filament]
created: 2026-07-14
updated: 2026-07-14
qmd: XotBase classes census for module extension patterns
---

# XotBase Classes Census

Censimento classi base in `Modules/Xot/app/` che devono essere estese invece delle classi native Filament/Laravel.

## Filament Widgets

| Classe Base | Path | Estende |
|-------------|------|---------|
| `XotBaseWidget` | `app/Filament/Widgets/XotBaseWidget.php` | `Filament\Widgets\Widget` |
| `XotBaseInfolistWidget` | `app/Filament/Widgets/XotBaseInfolistWidget.php` | `Filament\Widgets\Widget` |
| `XotBaseSchemaWidget` | `app/Filament/Widgets/XotBaseSchemaWidget.php` | `XotBaseWidget` |
| `XotBaseTableWidget` | `app/Filament/Widgets/XotBaseTableWidget.php` | `XotBaseWidget` |
| `XotBaseChartWidget` | `app/Filament/Widgets/XotBaseChartWidget.php` | `Filament\Widgets\ChartWidget` |
| `XotBaseStatsOverviewWidget` | `app/Filament/Widgets/XotBaseStatsOverviewWidget.php` | `Filament\Widgets\StatsOverviewWidget` |
| `XotBaseWizardWidget` | `app/Filament/Widgets/XotBaseWizardWidget.php` | `XotBaseWidget` |

## Filament Resources

| Classe Base | Path | Estende |
|-------------|------|---------|
| `XotBaseResource` | `app/Filament/Resources/XotBaseResource.php` | `Filament\Resources\Resource` |
| `XotBaseResourceForm` | `app/Filament/Resources/Schemas/XotBaseResourceForm.php` | - |
| `XotBaseResourceInfolist` | `app/Filament/Resources/Schemas/XotBaseResourceInfolist.php` | - |
| `XotBaseResourceTable` | `app/Filament/Resources/Tables/XotBaseResourceTable.php` | - |

## Filament Pages

| Classe Base | Path | Estende |
|-------------|------|---------|
| `XotBasePage` | `app/Filament/Pages/XotBasePage.php` | `Filament\Pages\Page` |
| `XotBaseDashboard` | `app/Filament/Pages/XotBaseDashboard.php` | `Filament\Pages\Dashboard` |
| `XotBaseListRecords` | `app/Filament/Resources/Pages/XotBaseListRecords.php` | `Filament\Resources\Pages\ListRecords` |
| `XotBaseCreateRecord` | `app/Filament/Resources/Pages/XotBaseCreateRecord.php` | `Filament\Resources\Pages\CreateRecord` |
| `XotBaseEditRecord` | `app/Filament/Resources/Pages/XotBaseEditRecord.php` | `Filament\Resources\Pages\EditRecord` |
| `XotBaseViewRecord` | `app/Filament/Resources/Pages/XotBaseViewRecord.php` | `Filament\Resources\Pages\ViewRecord` |

## Filament Forms Components

| Classe Base | Path | Estende |
|-------------|------|---------|
| `XotBaseField` | `app/Filament/Forms/Components/XotBaseField.php` | `Filament\Forms\Components\Field` |
| `XotBaseFormComponent` | `app/Filament/Forms/Components/XotBaseFormComponent.php` | - |
| `XotBaseSelect` | `app/Filament/Forms/Components/XotBaseSelect.php` | `Filament\Forms\Components\Select` |
| `XotBaseRadio` | `app/Filament/Forms/Components/XotBaseRadio.php` | `Filament\Forms\Components\Radio` |
| `XotBaseCheckboxList` | `app/Filament/Forms/Components/XotBaseCheckboxList.php` | `Filament\Forms\Components\CheckboxList` |
| `XotBaseDatePicker` | `app/Filament/Forms/Components/XotBaseDatePicker.php` | `Filament\Forms\Components\DatePicker` |

## Models

| Classe Base | Path | Estende |
|-------------|------|---------|
| `XotBaseModel` | `app/Models/XotBaseModel.php` | `Illuminate\Database\Eloquent\Model` |
| `XotBasePivot` | `app/Models/XotBasePivot.php` | `Illuminate\Database\Eloquent\Pivot` |
| `XotBaseMorphPivot` | `app/Models/XotBaseMorphPivot.php` | `Illuminate\Database\Eloquent\MorphPivot` |
| `XotBaseTreeModel` | `app/Models/XotBaseTreeModel.php` | `XotBaseModel` |
| `XotBaseUuidModel` | `app/Models/XotBaseUuidModel.php` | `XotBaseModel` |

## Policies

| Classe Base | Path | Estende |
|-------------|------|---------|
| `XotBasePolicy` | `app/Models/Policies/XotBasePolicy.php` | - |

## Providers

| Classe Base | Path | Estende |
|-------------|------|---------|
| `XotBaseServiceProvider` | `app/Providers/XotBaseServiceProvider.php` | `Illuminate\Support\ServiceProvider` |
| `XotBaseRouteServiceProvider` | `app/Providers/XotBaseRouteServiceProvider.php` | `Illuminate\Foundation\Support\Providers\RouteServiceProvider` |
| `XotBaseEventServiceProvider` | `app/Providers/XotBaseEventServiceProvider.php` | `Illuminate\Foundation\Support\Providers\EventServiceProvider` |

## Migrations

| Classe Base | Path | Estende |
|-------------|------|---------|
| `XotBaseMigration` | `app/Database/Migrations/XotBaseMigration.php` | `Illuminate\Database\Migrations\Migration` |

## View Components

| Classe Base | Path | Estende |
|-------------|------|---------|
| `XotBaseComponent` | `app/View/Components/XotBaseComponent.php` | `Illuminate\View\Component` |

## Regola PHPStan per `$view`

In `XotBaseWidget` il pattern corretto è:

```php
/** @var view-string */
/** @phpstan-ignore property.defaultValue */
protected string $view = '_params_xot';
```

Questo disabilita l'errore `property.defaultValue` per la proprietà `$view` tipizzata come `view-string` ma con default value `string`.
