<?php

declare(strict_types=1);

namespace Modules\Platform\Filament\Resources\AuditLogResource\Tables;

use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Modules\Platform\Filament\Resources\AuditLogResource;
use Modules\Platform\Models\AuditLog;
use Modules\Xot\Actions\Cast\SafeStringCastAction;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

/**
 * Tabella dedicata di AuditLogResource: la Resource delega qui via
 * XotBaseResource::table() (naming convention {Resource}\Tables\{ModelPlural}Table).
 * Non deve mai essere reintrodotto un override diretto di table() sulla Resource.
 */
class AuditLogsTable extends XotBaseResourceTable
{
    /**
     * @return array<string, TextColumn>
     */
    public function getTableColumns(): array
    {
        return [
            'created_at' => TextColumn::make('created_at')
                ->label(__('platform.audit_log.fields.created_at'))
                ->dateTime()
                ->sortable(),
            'actor.name' => TextColumn::make('actor.name')
                ->label(__('platform.audit_log.fields.actor'))
                ->placeholder('—')
                ->searchable(query: function (Builder $query, string $search): Builder {
                    return $query->whereHas('actor', function (Builder $q) use ($search): void {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
                }),
            'action' => TextColumn::make('action')
                ->label(__('platform.audit_log.fields.action'))
                ->badge()
                ->sortable(),
            'subject_type' => TextColumn::make('subject_type')
                ->label(__('platform.audit_log.fields.subject'))
                ->formatStateUsing(fn (string $state): string => class_basename($state))
                ->sortable(),
            'subject_id' => TextColumn::make('subject_id')
                ->label(__('platform.audit_log.fields.subject_id'))
                ->sortable(),
            'panel_id' => TextColumn::make('panel_id')
                ->label(__('platform.audit_log.fields.panel'))
                ->placeholder('—')
                ->toggleable(isToggledHiddenByDefault: true),
            'route' => TextColumn::make('route')
                ->label(__('platform.audit_log.fields.route'))
                ->placeholder('—')
                ->wrap()
                ->toggleable(isToggledHiddenByDefault: true),
            'ip' => TextColumn::make('ip')
                ->label(__('platform.audit_log.fields.ip'))
                ->placeholder('—')
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    /**
     * @return array<int, SelectFilter|Filter>
     */
    public function getTableFilters(): array
    {
        return [
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
                    ->mapWithKeys(fn (mixed $v): array => [
                        SafeStringCastAction::cast($v) => class_basename(SafeStringCastAction::cast($v)),
                    ])
                    ->all()),
            Filter::make('created_between')
                ->label(__('platform.audit_log.filters.date_range'))
                ->schema([
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
        ];
    }

    /**
     * @return array<int, ViewAction>
     */
    public function getTableActions(): array
    {
        return [
            ViewAction::make()
                ->label(__('platform.audit_log.actions.view'))
                ->url(fn (AuditLog $record): string => AuditLogResource::getUrl('view', ['record' => $record])),
        ];
    }

    protected function getDefaultTableSortColumn(): ?string
    {
        return 'created_at';
    }
}
