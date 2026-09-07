<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\HeatingSystemResource\RelationManagers;

use Filament\Actions\Action;
use Filament\Schemas\Components\Component;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Modules\Compliance\Actions\GenerateRceePdfAction;
use Modules\Compliance\Models\MaintenanceControl;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;

final class ControlsRelationManager extends XotBaseRelationManager
{
    protected static string $relationship = 'controls';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('heating.controls');
    }

    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        return [];
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('performed_on', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('control_type')

                    ->badge()
                    ->formatStateUsing(fn (string $state): string => __("heating.control_types.{$state}")),
                Tables\Columns\TextColumn::make('performed_on')->date()->sortable(),
                Tables\Columns\TextColumn::make('outcome')

                    ->badge()
                    ->color(fn (?string $state): string => $state === 'conforme' ? 'success' : ($state === 'non_conforme' ? 'danger' : 'gray'))
                    ->formatStateUsing(fn (?string $state): string => $state ? __("heating.outcomes.{$state}") : '—'),
                Tables\Columns\TextColumn::make('combustion_efficiency_percent')->suffix(' %')->toggleable(),
                Tables\Columns\TextColumn::make('next_due_on')->date()->sortable(),
                Tables\Columns\IconColumn::make('bollino_paid')->boolean()->toggleable(),
            ])
            ->recordActions([
                Action::make('rceePdf')

                    ->icon('heroicon-o-document-arrow-down')
                    ->action(function (MaintenanceControl $record) {
                        return response()->streamDownload(
                            fn () => print (app(GenerateRceePdfAction::class)->execute($record)),
                            sprintf('rcee-%d.pdf', $record->id),
                        );
                    }),
            ]);
    }
}
