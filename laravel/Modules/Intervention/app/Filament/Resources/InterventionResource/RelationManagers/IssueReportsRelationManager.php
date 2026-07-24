<?php

declare(strict_types=1);

namespace Modules\Intervention\Filament\Resources\InterventionResource\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\Intervention\Actions\CreateIssueReportAction;
use Modules\Intervention\Models\Intervention;
use Modules\Intervention\Models\IssueReport;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;

final class IssueReportsRelationManager extends XotBaseRelationManager
{
    protected static string $relationship = 'issueReports';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('interventions.issue_reports');
    }

    public function getFormSchema(): array
    {
        return [
            Select::make('created_by_user_id')
                ->label(__('interventions.fields.created_by'))
                ->relationship('createdBy', 'name')
                ->searchable()
                ->preload(),
            Textarea::make('body')
                ->label(__('interventions.fields.body'))
                ->required()
                ->columnSpanFull(),
        ];
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
                        /** @var Intervention $intervention */
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
