<?php

declare(strict_types=1);

namespace Modules\Intervention\Filament\Resources\InterventionResource\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Modules\Intervention\Actions\CreateIssueReportAction;
use Modules\Intervention\Models\IssueReport;

final class IssueReportsRelationManager extends RelationManager
{
    protected static string $relationship = 'issueReports';

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return __('interventions.issue_reports');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('created_by_user_id')
                ->label(__('interventions.fields.created_by'))
                ->relationship('createdBy', 'name')
                ->searchable()
                ->preload(),
            Textarea::make('body')
                ->label(__('interventions.fields.body'))
                ->required()
                ->columnSpanFull(),
        ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->label(__('interventions.fields.created_by'))
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('body')
                    ->label(__('interventions.fields.body'))
                    ->limit(80)
                    ->wrap(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->using(function (array $data): IssueReport {
                        /** @var \Modules\Intervention\Models\Intervention $intervention */
                        $intervention = $this->getOwnerRecord();

                        $createdBy = isset($data['created_by_user_id']) && $data['created_by_user_id']
                            ? (int) $data['created_by_user_id']
                            : (int) Auth::id();

                        return app(CreateIssueReportAction::class)->execute(
                            intervention: $intervention,
                            createdByUserId: $createdBy,
                            body: (string) $data['body'],
                        );
                    }),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }
}
