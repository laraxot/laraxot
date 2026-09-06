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
use Filament\Schemas\Components\Component;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\HR\Actions\AddEmployeeTrainingAction;
use Modules\HR\Models\Employee;
use Modules\HR\Models\EmployeeTraining;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;

final class TrainingsRelationManager extends XotBaseRelationManager
{
    protected static string $relationship = 'trainings';

    /**
     * @return array<int, Component>
     */
    public function getFormSchema(): array
    {
        return [
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
        ];
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
                            name: self::stringValue($data, 'name') ?? '',
                            completedAt: self::stringValue($data, 'completed_at'),
                            expiresAt: self::stringValue($data, 'expires_at'),
                            notes: self::stringValue($data, 'notes'),
                            title: self::stringValue($data, 'title'),
                            attachmentPath: self::stringValue($data, 'attachment_path'),
                            uploadedByUserId: is_int($uploadedByUserId) ? $uploadedByUserId : null,
                        );
                    }),
            ])
            ->recordActions([
                Action::make('download')
                    ->label(__('hr.actions.download'))
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
            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);
    }

    /**
     * Safely extract a nullable string from untyped Filament form data.
     *
     * @param  array<array-key, mixed>  $data
     */
    private static function stringValue(array $data, string $key): ?string
    {
        if (! array_key_exists($key, $data) || $data[$key] === null) {
            return null;
        }

        $value = $data[$key];

        if (is_string($value)) {
            return $value;
        }

        return is_scalar($value) ? (string) $value : null;
    }
}
