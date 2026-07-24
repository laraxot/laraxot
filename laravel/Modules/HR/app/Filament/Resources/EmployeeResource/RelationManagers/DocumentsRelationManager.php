<?php

declare(strict_types=1);

namespace Modules\HR\Filament\Resources\EmployeeResource\RelationManagers;

use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\HR\Actions\AddEmployeeDocumentAction;
use Modules\HR\Models\Employee;
use Modules\HR\Models\EmployeeDocument;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;

final class DocumentsRelationManager extends XotBaseRelationManager
{
    protected static string $relationship = 'documents';

    /**
     * @return array<int, Component>
     */
    public function getFormSchema(): array
    {
        return [
            TextInput::make('title')
                ->label(__('hr.fields.title'))
                ->maxLength(255),
            TextInput::make('kind')
                ->label(__('hr.fields.kind'))
                ->maxLength(255),
            FileUpload::make('path')
                ->label(__('hr.fields.file'))
                ->disk('public')
                ->directory('media/employees/documents')
                ->preserveFilenames()
                ->downloadable()
                ->openable()
                ->previewable()
                ->required()
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
                Tables\Columns\TextColumn::make('kind')
                    ->label(__('hr.fields.kind'))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('title')
                    ->label(__('hr.fields.title'))
                    ->description(fn (EmployeeDocument $record): string => basename($record->media->path))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->using(function (array $data, string $model): Model {
                        /** @var Employee $employee */
                        $employee = $this->getOwnerRecord();

                        $uploadedByUserId = Auth::id();

                        return app(AddEmployeeDocumentAction::class)->execute(
                            employee: $employee,
                            path: (string) $data['path'],
                            uploadedByUserId: is_int($uploadedByUserId) ? $uploadedByUserId : null,
                            title: isset($data['title']) ? (string) $data['title'] : null,
                            kind: isset($data['kind']) ? (string) $data['kind'] : null,
                        );
                    }),
            ])
            ->actions([
                Action::make('download')
                    ->label(__('hr.actions.download'))
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->url(fn (EmployeeDocument $record): string => $record->media->path !== ''
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
