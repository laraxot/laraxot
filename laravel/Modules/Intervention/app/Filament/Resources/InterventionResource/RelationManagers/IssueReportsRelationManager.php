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

    private static function ensureString(mixed $value): string
    {
        if (is_string($value)) {
            return $value;
        }

        if (is_numeric($value) || is_bool($value) || $value === null) {
            return (string) $value;
        }

        return '';
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('interventions.issue_reports');
    }

    public function getFormSchema(): array
    {
        return [
            Select::make('created_by_user_id')

                ->relationship('createdBy', 'name')
                ->searchable()
                ->preload(),
            Textarea::make('body')

                ->required()
                ->columnSpanFull(),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('createdBy.name')

                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('body')

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

                        $userIdValue = $data['created_by_user_id'] ?? null;
                        $createdBy = isset($userIdValue) && $userIdValue
                            ? (int) (is_numeric($userIdValue) ? $userIdValue : 0)
                            : (int) (Auth::id() ?? 0);

                        $bodyValue = $data['body'] ?? '';
                        $body = self::ensureString($bodyValue);

                        return app(CreateIssueReportAction::class)->execute(
                            intervention: $intervention,
                            createdByUserId: $createdBy,
                            body: $body,
                        );
                    }),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);
    }
}
