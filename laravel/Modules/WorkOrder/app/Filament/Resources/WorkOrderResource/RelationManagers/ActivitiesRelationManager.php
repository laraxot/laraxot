<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Filament\Resources\WorkOrderResource\RelationManagers;

use Carbon\CarbonImmutable;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Modules\WorkOrder\Actions\AddWorkOrderActivityAction;
use Modules\WorkOrder\Models\WorkOrderActivity;

final class ActivitiesRelationManager extends RelationManager
{
    protected static string $relationship = 'activities';

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return __('work_orders.activities');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('type')
                ->label(__('work_orders.fields.type'))
                ->options([
                    'note' => __('work_orders.activity_types.note'),
                    'status' => __('work_orders.activity_types.status'),
                ])
                ->default('note')
                ->required(),
            Textarea::make('body')
                ->label(__('work_orders.fields.body'))
                ->required()
                ->columnSpanFull(),
            DateTimePicker::make('occurred_at')
                ->label(__('work_orders.fields.occurred_at')),
        ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->label(__('work_orders.fields.type'))
                    ->badge(),
                Tables\Columns\TextColumn::make('body')
                    ->label(__('work_orders.fields.body'))
                    ->limit(60)
                    ->wrap(),
                Tables\Columns\TextColumn::make('occurred_at')
                    ->label(__('work_orders.fields.occurred_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->using(function (array $data): WorkOrderActivity {
                        $occurredAt = isset($data['occurred_at']) && $data['occurred_at']
                            ? CarbonImmutable::parse((string) $data['occurred_at'])
                            : null;

                        /** @var \Modules\WorkOrder\Models\WorkOrder $workOrder */
                        $workOrder = $this->getOwnerRecord();

                        return app(AddWorkOrderActivityAction::class)->execute(
                            workOrderId: (int) $workOrder->id,
                            type: (string) $data['type'],
                            body: (string) $data['body'],
                            createdByUserId: Auth::id(),
                            occurredAt: $occurredAt,
                        );
                    }),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
                RestoreBulkAction::make(),
                ForceDeleteBulkAction::make(),
            ]);
    }
}

