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
use Modules\HR\Actions\AddEmployeeMedicalVisitAction;
use Modules\HR\Models\Employee;
use Modules\HR\Models\EmployeeMedicalVisit;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;

final class MedicalVisitsRelationManager extends XotBaseRelationManager
{
    protected static string $relationship = 'medicalVisits';

    /**
     * @return array<int, Component>
     */
    public function getFormSchema(): array
    {
        return [
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
        ];
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
                    ->description(fn (EmployeeMedicalVisit $record): string => $record->media !== null ? basename($record->media->path) : '')
                    ->toggleable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->using(function (array $data, string $model): Model {
                        /** @var Employee $employee */
                        $employee = $this->getOwnerRecord();

                        $uploadedByUserId = Auth::id();

                        return app(AddEmployeeMedicalVisitAction::class)->execute(
                            employee: $employee,
                            visitedAt: (string) $data['visited_at'],
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
                    ->label(__('hr.actions.download'))
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->visible(fn (EmployeeMedicalVisit $record): bool => $record->media !== null)
                    ->url(fn (EmployeeMedicalVisit $record): string => $record->media !== null
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
