<?php

declare(strict_types=1);

namespace Modules\Platform\Filament\Resources\PlatformSettingResource\Tables;

use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Auth;
use Modules\Platform\Filament\Resources\PlatformSettingResource;
use Modules\Platform\Models\PlatformSetting;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

final class PlatformSettingsTable extends XotBaseResourceTable
{
    protected static string $resource = PlatformSettingResource::class;

    /**
     * @return array<string, TextColumn>
     */
    public function getTableColumns(): array
    {
        return [
            'key' => TextColumn::make('key')
                ->formatStateUsing(static fn (string $state): string => PlatformSettingResource::humanLabelForKey($state))
                ->searchable(),
            'value_display' => TextColumn::make('value_display')
                ->state(static fn (PlatformSetting $record): string => PlatformSettingResource::formatValueForDisplay($record))
                ->badge()
                ->color(static fn (PlatformSetting $record): string => PlatformSettingResource::badgeColorForValue($record)),
            'updated_by_user_id' => TextColumn::make('updatedBy.name')
                ->placeholder('—'),
            'updated_at' => TextColumn::make('updated_at')
                ->dateTime('d/m/Y H:i')
                ->sortable(),
        ];
    }

    /**
     * @return array<int, ViewAction|Action|EditAction>
     */
    public function getTableActions(): array
    {
        return [
            ViewAction::make(),
            Action::make('quick_edit')
                ->label('Modifica')
                ->icon('heroicon-o-pencil')
                ->slideOver()
                ->fillForm(static function (PlatformSetting $record): array {
                    /** @var mixed $raw */
                    $raw = data_get($record->value_json, 'value');

                    return ['quick_value' => $raw];
                })
                ->schema(static fn (PlatformSetting $record): array => PlatformSettingResource::quickEditFieldFor((string) $record->key))
                ->action(static function (PlatformSetting $record, array $data): void {
                    $record->update([
                        'value_json' => ['value' => $data['quick_value'] ?? null],
                        'updated_by_user_id' => Auth::id(),
                    ]);
                }),
            EditAction::make()
                ->label('Avanzato')
                ->icon('heroicon-o-cog-6-tooth'),
        ];
    }

    protected function getDefaultTableSortColumn(): string
    {
        return 'key';
    }

    protected function getDefaultTableSortDirection(): string
    {
        return 'asc';
    }
}
