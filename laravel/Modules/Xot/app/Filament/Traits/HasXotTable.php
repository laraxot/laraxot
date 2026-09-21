<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Traits;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\AssociateAction;
use Filament\Actions\AttachAction;
use Filament\Actions\BulkAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DetachAction;
use Filament\Actions\EditAction;
use Filament\Actions\ReplicateAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\ColumnGroup;
use Filament\Tables\Columns\Layout\Component as LayoutComponent;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Filters\BaseFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Str;
use Livewire\Component;
use Modules\UI\Enums\TableLayoutEnum;
use Modules\UI\Filament\Actions\Table\TableLayoutToggleTableAction;
use Modules\UI\Filament\Traits\HasTableLayoutPage;
use Modules\Xot\Actions\Cast\SafeStringCastAction;
use Modules\Xot\Actions\Filament\PlainTextFromFilamentValueAction;
use Modules\Xot\Actions\GetTransKeyAction;
use RuntimeException;
use Webmozart\Assert\Assert;

/**
 * Trait HasXotTable.
 *
 * Provides enhanced table functionality with translations and optimized structure.
 *
 * @property TableLayoutEnum $layoutView
 * @property string|null $tableSearch
 *
 * @SuppressWarnings("PHPMD.StaticAccess")
 * @SuppressWarnings("PHPMD.CyclomaticComplexity")
 * @SuppressWarnings("PHPMD.NPathComplexity")
 */
trait HasXotTable
{
    use HasTableLayoutPage;

    protected static bool $canReplicate = false;

    protected static bool $canView = true;

    protected static bool $canEdit = true;

    public function bootHasXotTable(): void
    {
        if (! $this instanceof Component) {
            return;
        }

        $this->mountTableLayoutFromSession();
    }

    /**
     * Get table header actions.
     *
     * CRITICO: Deve essere public perché viene chiamato da Filament/Livewire dall'esterno.
     * Filament\Tables\Concerns\InteractsWithTable richiede visibilità PUBLIC.
     * Vedi: Modules/Xot/docs/filament/widget-method-visibility-rules.md
     *
     * @return array<int|string, Action|ActionGroup>
     */
    public function getTableHeaderActions(): array
    {
        $resource = $this;
        /* @phpstan-ignore-next-line */
        if ($this instanceof ListRecords) {
            $resourceClass = $this->getResource();
            // @phpstan-ignore-next-line staticMethod.alreadyNarrowedType
            Assert::string($resourceClass);
            $resource = app($resourceClass);
        }

        // dddx(method_exists($resource, 'canAttach'));

        $actions = [
            CreateAction::make(),
        ];

        if ($this->shouldShowAssociateAction()) {
            $actions[] = AssociateAction::make()
                ->label('')
                ->icon('heroicon-o-paper-clip');
        }

        if (is_object($resource) && method_exists($resource, 'canAttach')) {
            $actions[] = AttachAction::make()
                ->icon('heroicon-o-link')
                ->iconButton()
                ->visible(static fn (): bool => (bool) $resource->canAttach());
        }

        $actions[] = TableLayoutToggleTableAction::make('layout');

        return $actions;
    }

    /**
     * Get grid table columns.
     *
     * In content-grid ogni riga mostra label e valore sulla stessa linea (es. «Ente: 123»).
     *
     * @return array<int, Column|ColumnGroup|LayoutComponent>
     */
    public function getGridTableColumns(): array
    {
        $columns = [];

        // @phpstan-ignore method.deprecated
        foreach (array_values($this->getTableColumns()) as $column) {
            if ($column instanceof ColumnGroup) {
                // Stack::make() non accetta ColumnGroup: nella vista a griglia le colonne
                // raggruppate non hanno un layout sensato, quindi vengono saltate.
                continue;
            }

            if (! $column instanceof Column && ! $column instanceof LayoutComponent) {
                // getTableColumns() può restituire, in alcuni contesti, elementi non tipizzati
                // (fallback deprecato di Filament): si scartano per restare coerenti col
                // tipo atteso da Stack::make().
                continue;
            }

            $gridColumn = clone $column;

            if ($gridColumn instanceof TextColumn) {
                $labelText = PlainTextFromFilamentValueAction::cast($gridColumn->getLabel());

                $gridColumn->formatStateUsing(
                    static function (mixed $state) use ($labelText): string {
                        if ($state === null || $state === '') {
                            return $labelText.': —';
                        }

                        return $labelText.': '.PlainTextFromFilamentValueAction::cast($state);
                    },
                );
            }

            $columns[] = $gridColumn;
        }

        return [
            Stack::make($columns)->space(1),
        ];
    }

    /**
     * Se i filtri vanno applicati solo dopo il bottone "Applica filtri" (default Filament)
     * oppure a ogni modifica del campo.
     *
     * Filament rende il bottone nel piede del form dei filtri, quindi occupa sempre una riga
     * propria: una tabella che vuole i filtri su una riga sola deve rinunciare al bottone e
     * applicare al volo. Il default `true` conserva il comportamento di tutte le tabelle
     * esistenti; si sovrascrive solo dove serve.
     */
    public function shouldDeferTableFilters(): bool
    {
        return true;
    }

    /**
     * Get table filters form columns.
     */
    public function getTableFiltersFormColumns(): int
    {
        // @phpstan-ignore method.deprecated
        $count = count($this->getTableFilters()) + 1;

        return min($count, 6);
    }

    /**
     * Get table record title attribute.
     */
    public function getTableRecordTitleAttribute(): string
    {
        return 'name';
    }

    /**
     * Configura una tabella Filament.
     *
     * Nota: Questo metodo è stato modificato per risolvere l'errore
     * "Method Filament\Actions\Action::table does not exist" in Filament 3.
     * La soluzione verifica l'esistenza dei metodi getTableHeaderActions(),
     * getTableActions() e getTableBulkActions() prima di chiamarli,
     * garantendo la compatibilità con diverse versioni di Filament.
     *
     * Problema: Il trait chiamava direttamente metodi che potrebbero non esistere
     * nelle classi che lo utilizzano, causando errori in Filament 3.
     *
     * Soluzione: Verifica condizionale dell'esistenza dei metodi prima di chiamarli,
     * mantenendo la retrocompatibilità e prevenendo errori.
     *
     * Ultimo aggiornamento: 10/2023
     */
    public function table(Table $table): Table
    {
        /*
        $modelClass = $this->getModelClass();
        if (! app(TableExistsByModelClassActions::class)->execute($modelClass)) {
            $this->notifyTableMissing();

            return $this->configureEmptyTable($table);
        }

        //  @var Model $model
        $model = app($modelClass);
        Assert::isInstanceOf($model, Model::class);
        */
        // Configurazione base della tabella
        // getTableColumns() può restituire, in alcuni contesti, elementi non tipizzati
        // (fallback deprecato di Filament): si filtrano per restare coerenti col tipo
        // atteso da TableLayoutEnum::getTableColumns().
        $tableColumns = array_values(array_filter(
            // @phpstan-ignore method.deprecated
            $this->getTableColumns(),
            static fn (mixed $column): bool => $column instanceof Column || $column instanceof ColumnGroup || $column instanceof LayoutComponent,
        ));

        $columns = $this->layoutView->getTableColumns($tableColumns, $this->getGridTableColumns());

        $table = $table
            ->recordTitleAttribute($this->getTableRecordTitleAttribute())
            // @phpstan-ignore method.deprecated
            ->heading($this->getTableHeading())
            // @phpstan-ignore method.deprecated, argument.type
            ->headerActions($this->getTableHeaderActions())
            ->columns($columns)
            ->contentGrid($this->layoutView->getTableContentGrid())
            // @phpstan-ignore method.deprecated
            ->filters($this->getTableFilters())
            ->filtersLayout($this->getTableFiltersLayout())
            ->filtersFormColumns($this->getTableFiltersFormColumns())
            ->deferFilters($this->shouldDeferTableFilters())
            // @phpstan-ignore method.deprecated
            ->persistFiltersInSession($this->shouldPersistTableFiltersInSession())
            // @phpstan-ignore method.deprecated, method.childReturnType, argument.type
            ->recordActions($this->getTableActions())
            // @phpstan-ignore method.deprecated
            ->toolbarActions($this->getTableBulkActions())
            ->recordActionsPosition($this->getTableRecordActionsPosition())
            // @phpstan-ignore method.deprecated
            ->emptyStateActions($this->getTableEmptyStateActions())
            // @phpstan-ignore method.deprecated
            ->striped($this->isTableStriped())
            ->paginated($this->getTablePaginated());

        // Configurazioni opzionali personalizzabili
        // @phpstan-ignore method.deprecated
        $sortColumn = $this->getDefaultTableSortColumn();
        // @phpstan-ignore method.deprecated
        $sortDirection = $this->getDefaultTableSortDirection();
        if ($sortColumn !== null && $sortDirection !== null) {
            $table = $table->defaultSort($sortColumn, $sortDirection);
        }

        $pollInterval = $this->getTablePollInterval();
        if ($pollInterval !== null) {
            $table = $table->poll($pollInterval);
        }

        $table = $this->applyReorderable($table);

        return $table;
    }

    /**
     * Get table filters.
     *
     * CRITICO: Deve essere public perché viene chiamato da Filament/Livewire dall'esterno.
     * Filament\Tables\Concerns\InteractsWithTable richiede visibilità PUBLIC.
     * Vedi: Modules/Xot/docs/filament/widget-method-visibility-rules.md
     *
     * @return array<string|int, \Filament\Tables\Filters\Filter|TernaryFilter|BaseFilter>
     */
    public function getTableFilters(): array
    {
        return [];
    }

    /**
     * Get table actions.
     *
     * CRITICO: Deve essere public perché viene chiamato da Filament/Livewire dall'esterno.
     * Vedi: Modules/Xot/docs/filament/widget-method-visibility-rules.md
     *
     * @return array<int|string, Action|ActionGroup>
     */
    public function getTableActions(): array
    {
        $actions = [];
        $resource = $this;
        /* @phpstan-ignore-next-line */
        // Ripristinato a method_exists() il 2026-09-11: un giro precedente
        // aveva ristretto questo controllo a `$this instanceof ListRecords`,
        // pensato per le pagine, ma XotBaseResourceTable::getTableActions()
        // (usato da OGNI {Model}sTable.php del repo, non solo dalle pagine
        // ListRecords) espone `getResource()` senza essere una ListRecords —
        // verificato dal vivo: la versione ristretta faceva sparire
        // view/edit/delete di default per tutte quelle tabelle (es.
        // ChartsTable::getTableActions() tornava [] invece di
        // view/edit/delete), zero errori, nessuna eccezione. Nessuna
        // giustificazione trovata per la versione ristretta (nessuna story
        // la documenta); il controllo ampio e' quello verificato corretto
        // per il resto di questa sessione.
        if (method_exists($this, 'getResource')) {
            $resourceClass = $this->getResource();
            // @phpstan-ignore-next-line staticMethod.alreadyNarrowedType
            Assert::string($resourceClass);
            $resource = app($resourceClass);
        }
        // @phpstan-ignore-next-line staticMethod.alreadyNarrowedType
        Assert::object($resource);
        

        // @phpstan-ignore-next-line function.alreadyNarrowedType
        if (method_exists($resource, 'canView')) {
            $actions['view'] = ViewAction::make()
                ->iconButton()
                ->visible(static fn (Model $record): bool => (bool) $resource->canView($record));
        }

        // @phpstan-ignore-next-line function.alreadyNarrowedType
        if (method_exists($resource, 'canEdit')) {
            $actions['edit'] = EditAction::make()
                ->iconButton()
                ->visible(static fn (Model $record): bool => (bool) $resource->canEdit($record));
        }

        // @phpstan-ignore-next-line function.alreadyNarrowedType
        if (method_exists($resource, 'canDelete')) {
            $actions['delete'] = DeleteAction::make()
                ->iconButton()
                ->visible(static fn (Model $record): bool => (bool) $resource->canDelete($record));
        }

        if ($this->shouldShowReplicateAction()) {
            $actions['replicate'] = ReplicateAction::make()
                ->iconButton();
        }

        // Check if class has the getRelationship method
        // Note: In some contexts (ListRecords), getRelationship() may not exist
        // @phpstan-ignore-next-line function.alreadyNarrowedType (needed for contexts where method doesn't exist)
        if ($this->shouldShowDetachAction() && method_exists($this, 'getRelationship')) {
            $relationship = $this->getRelationship();

            if ($relationship instanceof BelongsToMany) {
                $actions['detach'] = DetachAction::make()
                    ->iconButton()
                    ->tooltip((string) __('user::actions.detach'));
            }
        }

        return $actions;
    }

    /**
     * Get table bulk actions.
     *
     * CRITICO: Deve essere public perché viene chiamato da Filament/Livewire dall'esterno.
     * Filament\Tables\Concerns\InteractsWithTable richiede visibilità PUBLIC.
     * Vedi: Modules/Xot/docs/filament/widget-method-visibility-rules.md
     *
     * @return array<int|string, BulkAction>
     */
    public function getTableBulkActions(): array
    {
        return [
            'delete' => DeleteBulkAction::make()
                ->label('')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation(),
        ];
    }

    /**
     * Get model class.
     *
     *
     * @return class-string<Model>
     * @phpstan-return class-string<Model>
     *
     * @throws \Exception Se non viene trovata una classe modello valida
     */
    public function getModelClass(): string
    {
        // Su pagine "related records" / relation manager, getModel() risale al
        // model della Resource proprietaria (es. SurveyPdf), non al model della
        // relazione mostrata in tabella (es. Contact): va usato getRelationship().
        if (method_exists($this, 'getRelationship')) {
            $relationship = $this->getRelationship();
            Assert::isInstanceOfAny($relationship, [Relation::class, Builder::class]);
            $related = $relationship instanceof Builder ? $relationship->getModel() : $relationship->getRelated();
            if ($related instanceof Model) {
                /** @var class-string<Model> $relatedClass */
            $relatedClass = get_class($related);

            return $relatedClass;
            }
        }

        /* @phpstan-ignore-next-line function.alreadyNarrowedType */
        if (method_exists($this, 'getModel')) {
            $model = $this->getModel();
            Assert::string($model);
            if (! is_a($model, Model::class, true)) {
                throw new RuntimeException('Invalid model class '.$model);
            }

            return $model;
        }

        throw new RuntimeException('No model found in '.class_basename(self::class).'::'.__FUNCTION__);
    }

    /**
     * Get table search query.
     *
     * CRITICO: Deve essere public per rispettare il contratto ListRecords.
     */
    public function getTableSearch(): ?string
    {
        if (! property_exists($this, 'tableSearch')) {
            return null;
        }

        $tableSearch = $this->tableSearch;

        if (! filled($tableSearch)) {
            return null;
        }

        $trimmed = Str::trim(SafeStringCastAction::cast($tableSearch));

        return $trimmed !== '' ? $trimmed : null;
    }

    /**
     * Get list table columns.
     *
     * @return array<string, Column|ColumnGroup|LayoutComponent>
     */
    abstract protected function getTableColumns(): array;

    /**
     * Get table heading.
     */
    protected function getTableHeading(): ?string
    {
        /** @var string $transKey */
        $transKey = app(GetTransKeyAction::class)->execute(static::class);
        $key = Str::of($transKey)
            ->append('.table.heading')
            ->replace('.cluster.pages.', '.')
            ->toString();

        if (Str::startsWith($key, 'edit_')) {
            $key = Str::after($key, 'edit_');
        }

        if (Str::endsWith($key, '_widget')) {
            $key = Str::beforeLast($key, '_widget');
        }

        $trans = trans($key);

        if (! is_string($trans)) {
            return null;
        }

        return $trans !== $key ? $trans : null;
    }

    /**
     * Get table empty state actions.
     *
     * @return array<int|string, Action>
     */
    protected function getTableEmptyStateActions(): array
    {
        return [];
    }

    protected function shouldShowAssociateAction(): bool
    {
        return false;
    }

    protected function shouldShowAttachAction(): bool
    {
        // @phpstan-ignore-next-line
        return method_exists($this, 'getRelationship');
    }

    protected function shouldShowDetachAction(): bool
    {
        // @phpstan-ignore-next-line
        return method_exists($this, 'getRelationship');
    }

    protected function shouldShowReplicateAction(): bool
    {
        return static::$canReplicate;
    }

    protected function shouldShowViewAction(): bool
    {
        return static::$canView;
    }

    protected function shouldShowEditAction(): bool
    {
        return static::$canEdit;
    }

    /**
     * Get header actions.
     *
     * @return array<string, Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            'create' => CreateAction::make()->icon('heroicon-o-plus'),
        ];
    }

    /**
     * Get table filters layout.
     */
    protected function getTableFiltersLayout(): FiltersLayout
    {
        return FiltersLayout::AboveContent;
    }

    /**
     * Get table record actions position.
     */
    protected function getTableRecordActionsPosition(): RecordActionsPosition
    {
        return RecordActionsPosition::BeforeColumns;
    }

    /**
     * Whether table rows are striped.
     */
    protected function isTableStriped(): bool
    {
        return true;
    }

    /**
     * Whether table filters persist in session.
     */
    protected function shouldPersistTableFiltersInSession(): bool
    {
        return true;
    }

    /**
     * Get table pagination options.
     * Can return bool (true/false) or array of page sizes [10, 25, 50, 100].
     *
     * @return bool|array<int, int|string>
     */
    protected function getTablePaginated(): bool|array
    {
        return true;
    }

    /**
     * Get default table sort column.
     */
    protected function getDefaultTableSortColumn(): ?string
    {
        try {
            $modelClass = $this->getModelClass();
            /** @var Model $model */
            $model = app($modelClass);
            Assert::isInstanceOf($model, Model::class);

            return $model->getTable().'.id';
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get default table sort direction.
     */
    protected function getDefaultTableSortDirection(): ?string
    {
        return 'desc';
    }

    /**
     * Get table polling interval.
     * Returns null to disable polling, or a string like '30s' to enable.
     */
    protected function getTablePollInterval(): ?string
    {
        return null;
    }

    /**
     * Notify that table is missing.
     */
    protected function notifyTableMissing(): void
    {
        $modelClass = $this->getModelClass();
        /** @var Model $model */
        $model = app($modelClass);
        Assert::isInstanceOf($model, Model::class);

        Notification::make()
            ->title((string) __('user::notifications.table_missing.title'))
            ->body((string) __('user::notifications.table_missing.body', [
                'table' => $model->getTable(),
            ]))
            ->persistent()
            ->warning()
            ->send();
    }

    /**
     * Configure empty table.
     */
    protected function configureEmptyTable(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(
                static fn (Builder $query): Builder => $query->whereNull('id')
            )
            ->columns([
                TextColumn::make('message')
                    ->default(__('user::fields.message.default'))
                    ->html(),
            ])
            ->headerActions([])
            ->recordActions([]);
    }

    /**
     * Get searchable columns.
     *
     * @return array<string>
     */
    protected function getSearchableColumns(): array
    {
        return ['id', 'name'];
    }

    /**
     * Check if search is enabled.
     */
    protected function hasSearch(): bool
    {
        return true;
    }

    /**
     * Check if model has a specific column via schema introspection.
     * Generic, reusable method for any column check.
     */
    protected function hasColumn(string $column): bool
    {
        $modelClass = $this->getModelClass();
        $model = app($modelClass);
        Assert::isInstanceOf($model, Model::class);

        return $model->getConnection()
            ->getSchemaBuilder()
            ->hasColumn($model->getTable(), $column);
    }

    /**
     * Get the column name used for table reordering.
     * Returns 'order_column' if it exists in the model's table, null otherwise.
     */
    protected function getOrderColumn(): ?string
    {
        if ($this->hasColumn('order_column')) {
            return 'order_column';
        }
    
        return null;
    }

    /**
     * Apply reorderable to table if order column is available.
     */
    protected function applyReorderable(Table $table): Table
    {
        $orderColumn = $this->getOrderColumn();
       

        if ($orderColumn !== null) {
            return $table->reorderable($orderColumn);
        }

        return $table;
    }
}
