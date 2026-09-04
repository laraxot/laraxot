<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Filament\Resources\WorkOrderResource\RelationManagers;

use Carbon\CarbonImmutable;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\WorkOrder\Actions\AddWorkOrderActivityAction;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\WorkOrder\Models\WorkOrderActivity;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;

final class ActivitiesRelationManager extends XotBaseRelationManager
{
    protected static string $relationship = 'activities';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('workorder::work_orders.activities');
    }

    public function getFormSchema(): array
    {
        return [
            Select::make('type')
                ->options([
                    'note' => __('workorder::work_orders.activity_types.note'),
                    'status' => __('workorder::work_orders.activity_types.status'),
                ])
                ->default('note')
                ->required(),
            Textarea::make('body')
                ->required()
                ->columnSpanFull(),
            DateTimePicker::make('occurred_at'),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->badge(),
                Tables\Columns\TextColumn::make('body')
                    ->limit(60)
                    ->wrap(),
                Tables\Columns\TextColumn::make('occurred_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->using(function (array $data): WorkOrderActivity {
                        $occurredAt = isset($data['occurred_at']) && $data['occurred_at']
                            ? CarbonImmutable::parse((string) $data['occurred_at'])
                            : null;

                        /** @var WorkOrder $workOrder */
                        $workOrder = $this->getOwnerRecord();

                        return app(AddWorkOrderActivityAction::class)->execute(
                            workOrderId: (int) $workOrder->id,
                            type: (string) $data['type'],
                            body: (string) $data['body'],
                            createdByUserId: (($authId = Auth::id()) !== null ? (int) $authId : null),
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
