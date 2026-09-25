# Datas in Blog Module

All data objects in the Blog module use the `Spatie\LaravelData\Data` contract for type safety and serialization.

## Available Data Classes

Located in `Blog/app/Datas/`:

- `ArticleData` - Article entity representation
- `ArticleImportMetricsData` - Metrics for article import operations
- `ArticleImportScoresData` - Scoring results for imported articles
- `ArticleDataHydrated` - Fully loaded article with relations

## Usage Examples

### From Controller or Action
```php
use Modules\Blog\app\Datas\ArticleData;

// Create from array
$article = ArticleData::from($request->validated());

// Create from Eloquent model
$article = ArticleData::fromModel($articleModel);

// Validate incoming data
$article = ArticleData::validate($input);
```

### In Filament Forms
```php
use Modules\Blog\app\Datas\ArticleData;

Forms\Form::make()
    ->schema([
        Forms\Components\TextInput::make('title')
            ->label('Article Title')
            ->required()
            ->maxLength(255),
        // ... other fields
    ])
    ->model(ArticleData::class);
```

## Migration Notes

Previously located in `app/dataobjects/` and `app/DataObjects/` directories, all data classes were migrated to `app/Datas/` following the Laraxot standard:
- All classes extend `Spatie\LaravelData\Data`
- Naming convention: `*Data.php` (not `*Dto.php`)
- Automatic serialization/deserialization support
- Full PHPStan level max compliance