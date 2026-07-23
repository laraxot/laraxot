<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\HeatingSystemResource\RelationManagers;

use Filament\Actions\Action;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Compliance\Actions\GenerateRceePdfAction;
use Modules\Compliance\Models\MaintenanceControl;

final class ControlsRelationManager extends RelationManager
{
    protected static string $relationship = 'controls';

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return __('heating.controls');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('performed_on', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('control_type')
                    ->label(__('heating.fields.control_type'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => __("heating.control_types.{$state}")),
                Tables\Columns\TextColumn::make('performed_on')->label(__('heating.fields.performed_on'))->date()->sortable(),
                Tables\Columns\TextColumn::make('outcome')
                    ->label(__('heating.fields.outcome'))
                    ->badge()
                    ->color(fn (?string $state): string => $state === 'conforme' ? 'success' : ($state === 'non_conforme' ? 'danger' : 'gray'))
                    ->formatStateUsing(fn (?string $state): string => $state ? __("heating.outcomes.{$state}") : '—'),
                Tables\Columns\TextColumn::make('combustion_efficiency_percent')->label(__('heating.fields.efficiency_percent'))->suffix(' %')->toggleable(),
                Tables\Columns\TextColumn::make('next_due_on')->label(__('heating.fields.next_due_on'))->date()->sortable(),
                Tables\Columns\IconColumn::make('bollino_paid')->label(__('heating.fields.bollino'))->boolean()->toggleable(),
            ])
            ->actions([
                Action::make('rceePdf')
                    ->label(__('heating.actions.download_rcee'))
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
