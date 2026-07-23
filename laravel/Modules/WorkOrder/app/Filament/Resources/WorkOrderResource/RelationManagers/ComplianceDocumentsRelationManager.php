<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Filament\Resources\WorkOrderResource\RelationManagers;

use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\Compliance\Actions\AttachComplianceDocumentFileAction;
use Modules\Compliance\Actions\GeneratePosDraftForWorkOrderAction;
use Modules\Compliance\Models\ComplianceDocument;
use Modules\WorkOrder\Models\WorkOrder;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

final class ComplianceDocumentsRelationManager extends RelationManager
{
    protected static string $relationship = 'complianceDocuments';

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return __('compliance.documents');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(3)->components([
                Select::make('type')->label(__('compliance.fields.type'))->required()->options([
                    'durc' => 'DURC',
                    'pos' => 'POS',
                    'psc' => 'PSC',
                    'dvr' => 'DVR',
                    'dico' => 'DICO',
                    'building_permit' => 'Titolo edilizio',
                    'insurance' => 'Polizza',
                    'other' => 'Altro',
                ]),
                TextInput::make('title')->label(__('compliance.fields.title'))->required()->maxLength(255),
                Select::make('status')->label(__('compliance.fields.status'))->default('missing')->options([
                    'missing' => 'Mancante',
                    'valid' => 'Valido',
                    'expiring' => 'In scadenza',
                    'expired' => 'Scaduto',
                    'waived' => 'Non necessario',
                ]),
                Toggle::make('is_required')->label(__('compliance.fields.is_required'))->default(true),
                DatePicker::make('issued_on')->label(__('compliance.fields.issued_on')),
                DatePicker::make('expires_on')->label(__('compliance.fields.expires_on')),
            ]),
            Textarea::make('notes')->label(__('compliance.fields.notes'))->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')->label(__('compliance.fields.type'))->badge()->sortable(),
                Tables\Columns\TextColumn::make('title')->label(__('compliance.fields.title'))->searchable(),
                Tables\Columns\TextColumn::make('status')->label(__('compliance.fields.status'))->badge()->sortable(),
                Tables\Columns\TextColumn::make('expires_on')->label(__('compliance.fields.expires_on'))->date()->sortable(),
                Tables\Columns\IconColumn::make('is_required')->label(__('compliance.fields.is_required'))->boolean(),
            ])
            ->headerActions([
                Action::make('generatePosDraft')
                    ->label(__('compliance.actions.generate_pos_draft'))
                    ->icon('heroicon-o-document-plus')
                    ->color('success')
                    ->action(function (): void {
                        /** @var WorkOrder $workOrder */
                        $workOrder = $this->getOwnerRecord();
                        app(GeneratePosDraftForWorkOrderAction::class)->execute($workOrder);
                    }),
                CreateAction::make()
                    ->form($this->createFormSchema())
                    ->using(function (array $data, string $model): Model {
                        $filePath = isset($data['file_path']) ? (string) $data['file_path'] : null;
                        unset($data['file_path']);

                        /** @var ComplianceDocument $document */
                        $document = $this->getRelationship()->create($data);

                        if ($filePath !== null && trim($filePath) !== '') {
                            app(AttachComplianceDocumentFileAction::class)->execute(
                                document: $document,
                                path: $filePath,
                                uploadedByUserId: Auth::id(),
                            );
                        }

                        return $document;
                    }),
            ])
            ->actions([EditAction::make(), DeleteAction::make()]);
    }

    /**
     * @return array<int, \Filament\Schemas\Components\Component>
     */
    private function createFormSchema(): array
    {
        return [
            Grid::make(3)->components([
                Select::make('type')->label(__('compliance.fields.type'))->required()->options([
                    'durc' => 'DURC',
                    'pos' => 'POS',
                    'psc' => 'PSC',
                    'dvr' => 'DVR',
                    'dico' => 'DICO',
                    'building_permit' => 'Titolo edilizio',
                    'insurance' => 'Polizza',
                    'other' => 'Altro',
                ]),
                TextInput::make('title')->label(__('compliance.fields.title'))->required()->maxLength(255),
                Select::make('status')->label(__('compliance.fields.status'))->default('missing')->options([
                    'missing' => 'Mancante',
                    'valid' => 'Valido',
                    'expiring' => 'In scadenza',
                    'expired' => 'Scaduto',
                    'waived' => 'Non necessario',
                ]),
                Toggle::make('is_required')->label(__('compliance.fields.is_required'))->default(true),
                DatePicker::make('issued_on')->label(__('compliance.fields.issued_on')),
                DatePicker::make('expires_on')->label(__('compliance.fields.expires_on')),
            ]),
            FileUpload::make('file_path')
                ->label(__('compliance.fields.file'))
                ->disk('public')
                ->directory('media/compliance')
                ->preserveFilenames()
                ->downloadable()
                ->openable()
                ->previewable()
                ->columnSpanFull(),
            Textarea::make('notes')->label(__('compliance.fields.notes'))->columnSpanFull(),
        ];
    }
}
