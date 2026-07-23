<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Filament\Resources\WorkOrderResource\RelationManagers;

use App\Models\User;
use Carbon\CarbonImmutable;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Modules\WorkOrder\Actions\AssignUserToWorkOrderAction;
use Modules\WorkOrder\Actions\UnassignUserFromWorkOrderAction;
use Modules\WorkOrder\Models\WorkOrderAssignment;

final class AssignmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'assignments';

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return __('work_orders.assignments');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('user_id')
                ->label(__('work_orders.fields.assigned_to'))
                ->options(fn () => User::query()
                    ->orderBy('name')
                    ->get()
                    ->mapWithKeys(fn (User $user): array => [$user->id => $user->name])
                    ->all())
                ->searchable()
                ->preload()
                ->required(),
            TextInput::make('role')
                ->label(__('work_orders.fields.role'))
                ->maxLength(255),
            DateTimePicker::make('assigned_at')
                ->label(__('work_orders.fields.assigned_at')),
        ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('work_orders.fields.assigned_to'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('role')
                    ->label(__('work_orders.fields.role'))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('assigned_at')
                    ->label(__('work_orders.fields.assigned_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->using(function (array $data): WorkOrderAssignment {
                        $assignedAt = isset($data['assigned_at']) && $data['assigned_at']
                            ? CarbonImmutable::parse((string) $data['assigned_at'])
                            : null;

                        /** @var \Modules\WorkOrder\Models\WorkOrder $workOrder */
                        $workOrder = $this->getOwnerRecord();

                        return app(AssignUserToWorkOrderAction::class)->execute(
                            workOrderId: (int) $workOrder->id,
                            userId: (int) $data['user_id'],
                            role: $data['role'] ?? null,
                            assignedByUserId: Auth::id(),
                            assignedAt: $assignedAt,
                        );
                    }),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make()
                    ->using(function (WorkOrderAssignment $record): void {
                        app(UnassignUserFromWorkOrderAction::class)->execute(
                            workOrderId: (int) $record->work_order_id,
                            userId: (int) $record->user_id,
                        );
                    }),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }
}

