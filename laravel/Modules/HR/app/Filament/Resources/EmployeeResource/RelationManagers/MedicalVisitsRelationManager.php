<?php

declare(strict_types=1);

namespace Modules\HR\Filament\Resources\EmployeeResource\RelationManagers;

use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\HR\Actions\AddEmployeeMedicalVisitAction;
use Modules\HR\Models\Employee;

final class MedicalVisitsRelationManager extends RelationManager
{
    protected static string $relationship = 'medicalVisits';

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return __('hr.medical_visits');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('title')
                ->label(__('hr.fields.title'))
                ->maxLength(255),
            DatePicker::make('visited_at')
                ->label(__('hr.fields.visited_at'))
                ->required(),
            DatePicker::make('expires_at')
                ->label(__('hr.fields.expires_at')),
            FileUpload::make('attachment_path')
                ->label(__('hr.fields.file'))
                ->disk('public')
                ->directory('media/employees/medical-visits')
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
                Tables\Columns\TextColumn::make('title')
                    ->label(__('hr.fields.title'))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('visited_at')
                    ->label(__('hr.fields.visited_at'))
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('expires_at')
                    ->label(__('hr.fields.expires_at'))
                    ->date()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->label(__('hr.fields.title'))
                    ->description(fn ($record): string => basename((string) ($record->attachment_path ?? '')))
                    ->toggleable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->using(function (array $data, string $model): Model {
                        /** @var Employee $employee */
                        $employee = $this->getOwnerRecord();

                        return app(AddEmployeeMedicalVisitAction::class)->execute(
                            employee: $employee,
                            visitedAt: (string) $data['visited_at'],
                            expiresAt: $data['expires_at'] ?? null,
                            notes: $data['notes'] ?? null,
                            title: $data['title'] ?? null,
                            attachmentPath: $data['attachment_path'] ?? null,
                            uploadedByUserId: Auth::id(),
                        );
                    }),
            ])
            ->actions([
                Action::make('download')
                    ->label(__('documents.actions.download'))
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->visible(fn ($record): bool => (bool) ($record->attachment_path ?? false))
                    ->url(fn ($record): string => $record->attachment_path
                        ? asset('storage/' . ltrim((string) $record->attachment_path, '/'))
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

