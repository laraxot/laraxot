<?php

declare(strict_types=1);

namespace Modules\Platform\Filament\Resources;

use App\Support\AccountFeatures;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Modules\Platform\Filament\Resources\AuditLogResource\Pages\ListAuditLogs;
use Modules\Platform\Filament\Resources\AuditLogResource\Pages\ViewAuditLog;
use Modules\Platform\Models\AuditLog;
use Modules\Xot\Filament\Resources\XotBaseResource;

final class AuditLogResource extends XotBaseResource
{
    protected static ?string $model = AuditLog::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-shield-check';

    protected static ?int $navigationSort = 5;

    public static function shouldRegisterNavigation(): bool
    {
        return self::isAvailableInCurrentPanel();
    }

    public static function canViewAny(): bool
    {
        return self::isAvailableInCurrentPanel() && parent::canViewAny();
    }

    public static function canView(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return self::isAvailableInCurrentPanel() && parent::canView($record);
    }

    public static function getNavigationGroup(): string
    {
        return __('platform.settings');
    }

    public static function getNavigationLabel(): string
    {
        return __('platform.audit_log.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('platform.audit_log.model');
    }

    public static function getPluralModelLabel(): string
    {
        return __('platform.audit_log.model_plural');
    }

    public static function getFormSchema(): array
    {
        return [];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')
                    ->label(__('platform.audit_log.fields.created_at'))
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('actor.name')
                    ->label(__('platform.audit_log.fields.actor'))
                    ->placeholder('—')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('actor', function (Builder $q) use ($search): void {
                            $q->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                    }),
                TextColumn::make('action')
                    ->label(__('platform.audit_log.fields.action'))
                    ->badge()
                    ->sortable(),
                TextColumn::make('subject_type')
                    ->label(__('platform.audit_log.fields.subject'))
                    ->formatStateUsing(fn (string $state): string => class_basename($state))
                    ->sortable(),
                TextColumn::make('subject_id')
                    ->label(__('platform.audit_log.fields.subject_id'))
                    ->sortable(),
                TextColumn::make('panel_id')
                    ->label(__('platform.audit_log.fields.panel'))
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('route')
                    ->label(__('platform.audit_log.fields.route'))
                    ->placeholder('—')
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('ip')
                    ->label(__('platform.audit_log.fields.ip'))
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('action')
                    ->label(__('platform.audit_log.filters.action'))
                    ->options([
                        'created' => __('platform.audit_log.actions.created'),
                        'updated' => __('platform.audit_log.actions.updated'),
                        'deleted' => __('platform.audit_log.actions.deleted'),
                        'restored' => __('platform.audit_log.actions.restored'),
                        'force_deleted' => __('platform.audit_log.actions.force_deleted'),
                    ]),
                SelectFilter::make('subject_type')
                    ->label(__('platform.audit_log.filters.subject_type'))
                    ->options(fn (): array => AuditLog::query()
                        ->select('subject_type')
                        ->distinct()
                        ->orderBy('subject_type')
                        ->pluck('subject_type', 'subject_type')
                        ->mapWithKeys(fn (mixed $v): array => [(string) $v => class_basename((string) $v)])
                        ->all()),
                Filter::make('created_between')
                    ->label(__('platform.audit_log.filters.date_range'))
                    ->form([
                        DatePicker::make('from')->label(__('platform.audit_log.filters.from')),
                        DatePicker::make('to')->label(__('platform.audit_log.filters.to')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $from = $data['from'] ?? null;
                        $to = $data['to'] ?? null;

                        if (is_string($from) && $from !== '') {
                            $query->whereDate('created_at', '>=', $from);
                        }
                        if (is_string($to) && $to !== '') {
                            $query->whereDate('created_at', '<=', $to);
                        }

                        return $query;
                    }),
            ])
            ->actions([
                ViewAction::make()
                    ->label(__('platform.audit_log.actions.view'))
                    ->url(fn (AuditLog $record): string => self::getUrl('view', ['record' => $record])),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with('actor');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAuditLogs::route('/'),
            'view' => ViewAuditLog::route('/{record}'),
        ];
    }

    private static function isAvailableInCurrentPanel(): bool
    {
        return AccountFeatures::currentPanelAllowsFeature(
            Auth::user(),
            adminFeature: 'admin.audit_log',
            operatorFeature: null,
            adminDefault: true,
            operatorDefault: false,
            fallback: false,
        );
    }
}
