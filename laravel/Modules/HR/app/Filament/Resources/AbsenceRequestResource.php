<?php

declare(strict_types=1);

namespace Modules\HR\Filament\Resources;

use App\Support\Concerns\HasAccountFeatureVisibility;
use Filament\Actions\Action;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Modules\HR\Actions\ApproveAbsenceRequestAction;
use Modules\HR\Actions\RejectAbsenceRequestAction;
use Modules\HR\Filament\Resources\AbsenceRequestResource\Pages;
use Modules\HR\Models\AbsenceRequest;

final class AbsenceRequestResource extends Resource
{
    use HasAccountFeatureVisibility;

    /** @var array<string, string> */
    private const ABSENCE_TYPE_ALIASES = [
        'permesso' => 'leave',
        'ferie' => 'vacation',
        'malattia' => 'sick',
        'infortunio' => 'injury',
    ];

    protected static ?string $model = AbsenceRequest::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calendar';

    private const ACCOUNT_FEATURE_ADMIN = 'admin.hr';

    private const ACCOUNT_FEATURE_OPERATOR = 'operator.hr';

    public static function getNavigationGroup(): ?string
    {
        return __('hr.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('hr.absence_request');
    }

    public static function getPluralModelLabel(): string
    {
        return __('hr.absence_requests');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()
                ->columnSpanFull()
                ->components([
                    Grid::make(2)->components([
                        Select::make('type')
                            ->label(__('hr.fields.absence_type'))
                            ->options(self::getAbsenceTypeOptions())
                            ->afterStateHydrated(function (Select $component, ?string $state): void {
                                $component->state(self::normalizeAbsenceType($state));
                            })
                            ->dehydrateStateUsing(fn (?string $state): ?string => self::normalizeAbsenceType($state))
                            ->required(),

                        Select::make('status')
                            ->label(__('hr.fields.status'))
                            ->options([
                                'pending' => __('hr.statuses.pending'),
                                'approved' => __('hr.statuses.approved'),
                                'rejected' => __('hr.statuses.rejected'),
                            ])
                            ->disabled(fn (): bool => request()->is('operator/*')),
                    ]),

                    Grid::make(2)->components([
                        DateTimePicker::make('starts_at')
                            ->label(__('hr.fields.starts_at'))
                            ->required(),
                        DateTimePicker::make('ends_at')
                            ->label(__('hr.fields.ends_at'))
                            ->required(),
                    ]),

                    Textarea::make('notes')
                        ->label(__('hr.fields.notes'))
                        ->columnSpanFull(),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->label(__('hr.fields.absence_type'))
                    ->formatStateUsing(fn (?string $state): string => self::translateAbsenceType($state))
                    ->badge(),
                Tables\Columns\TextColumn::make('starts_at')
                    ->label(__('hr.fields.starts_at'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ends_at')
                    ->label(__('hr.fields.ends_at'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('hr.fields.status'))
                    ->formatStateUsing(fn (string $state): string => __("hr.statuses.{$state}"))
                    ->badge()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('hr.fields.status'))
                    ->options([
                        'pending' => __('hr.statuses.pending'),
                        'approved' => __('hr.statuses.approved'),
                        'rejected' => __('hr.statuses.rejected'),
                    ])
                    ->default('pending'),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('approve')
                    ->label(__('hr.actions.approve'))
                    ->color('success')
                    ->visible(fn (AbsenceRequest $record): bool => request()->is('admin/*') && $record->status === 'pending')
                    ->requiresConfirmation()
                    ->action(fn (AbsenceRequest $record): AbsenceRequest => app(ApproveAbsenceRequestAction::class)->execute($record, (int) Auth::id())),
                Action::make('reject')
                    ->label(__('hr.actions.reject'))
                    ->color('danger')
                    ->visible(fn (AbsenceRequest $record): bool => request()->is('admin/*') && $record->status === 'pending')
                    ->requiresConfirmation()
                    ->action(fn (AbsenceRequest $record): AbsenceRequest => app(RejectAbsenceRequestAction::class)->execute($record, (int) Auth::id())),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (request()->is('operator/*')) {
            $userId = Auth::id();
            if ($userId !== null) {
                $query->where('user_id', $userId);
            }
        }

        return $query;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAbsenceRequests::route('/'),
            'create' => Pages\CreateAbsenceRequest::route('/create'),
            'view' => Pages\ViewAbsenceRequest::route('/{record}'),
            'edit' => Pages\EditAbsenceRequest::route('/{record}/edit'),
        ];
    }

    /** @return array<string, string> */
    private static function getAbsenceTypeOptions(): array
    {
        return [
            'leave' => __('hr.absence_types.leave'),
            'vacation' => __('hr.absence_types.vacation'),
            'sick' => __('hr.absence_types.sick'),
            'injury' => __('hr.absence_types.injury'),
        ];
    }

    private static function translateAbsenceType(?string $type): string
    {
        if ($type === null || trim($type) === '') {
            return '-';
        }

        $normalized = self::normalizeAbsenceType($type);
        if ($normalized === null || trim($normalized) === '') {
            return '-';
        }

        $key = "hr.absence_types.{$normalized}";

        if (Lang::has($key)) {
            return __($key);
        }

        return ucfirst(str_replace('_', ' ', $type));
    }

    private static function normalizeAbsenceType(?string $type): ?string
    {
        if ($type === null || trim($type) === '') {
            return $type;
        }

        $normalized = strtolower(trim($type));

        return self::ABSENCE_TYPE_ALIASES[$normalized] ?? $normalized;
    }
}

