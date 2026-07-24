<?php

declare(strict_types=1);

namespace Modules\HR\Filament\Resources\EmployeeResource\RelationManagers;

use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\HR\Actions\AddEmployeeTrainingAction;
use Modules\HR\Models\Employee;
use Modules\HR\Models\EmployeeTraining;

final class TrainingsRelationManager extends RelationManager
{
    protected static string $relationship = 'trainings';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('hr.trainings');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('title')
                ->label(__('hr.fields.title'))
                ->maxLength(255),
            TextInput::make('name')
                ->label(__('hr.fields.name'))
                ->required()
                ->maxLength(255),
            DatePicker::make('completed_at')
                ->label(__('hr.fields.completed_at')),
            DatePicker::make('expires_at')
                ->label(__('hr.fields.expires_at')),
            FileUpload::make('attachment_path')
                ->label(__('hr.fields.file'))
                ->disk('public')
                ->directory('media/employees/trainings')
                ->preserveFilenames()
                ->downloadable()
                ->openable()
                ->previewable()
                ->columnSpanFull(),
            Textarea::make('notes')
                ->label(__('hr.fields.notes'))
                ->columnSpanFull(),
        ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('hr.fields.name'))
                    ->searchable()
                    ->sortable()
                    ->description(fn (EmployeeTraining $record): string => $record->media !== null ? basename($record->media->path) : ''),
                Tables\Columns\TextColumn::make('expires_at')
                    ->label(__('hr.fields.expires_at'))
                    ->date()
                    ->toggleable()
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->using(function (array $data, string $model): Model {
                        /** @var Employee $employee */
                        $employee = $this->getOwnerRecord();

                        $uploadedByUserId = Auth::id();

                        return app(AddEmployeeTrainingAction::class)->execute(
                            employee: $employee,
                            name: (string) $data['name'],
                            completedAt: isset($data['completed_at']) ? (string) $data['completed_at'] : null,
                            expiresAt: isset($data['expires_at']) ? (string) $data['expires_at'] : null,
                            notes: isset($data['notes']) ? (string) $data['notes'] : null,
                            title: isset($data['title']) ? (string) $data['title'] : null,
                            attachmentPath: isset($data['attachment_path']) ? (string) $data['attachment_path'] : null,
                            uploadedByUserId: is_int($uploadedByUserId) ? $uploadedByUserId : null,
                        );
                    }),
            ])
            ->actions([
                Action::make('download')
                    ->label(__('documents.actions.download'))
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->visible(fn (EmployeeTraining $record): bool => $record->media !== null)
                    ->url(fn (EmployeeTraining $record): string => $record->media !== null
                        ? asset('storage/'.ltrim($record->media->path, '/'))
                        : '#'
                    )
                    ->openUrlInNewTab(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }
}
