<?php

declare(strict_types=1);

namespace Modules\Intervention\Filament\Resources;

use App\Support\AccountFeatures;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\RichEditor\TextColor;
use Filament\Forms\Components\Select;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\Intervention\Filament\Resources\DailyNoteResource\Pages;
use Modules\Intervention\Models\DailyNote;
use Modules\Platform\Models\PlatformSetting;
use Modules\Xot\Filament\Resources\XotBaseResource;

use function Safe\preg_replace;

final class DailyNoteResource extends XotBaseResource
{
    protected static ?string $model = DailyNote::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-pencil-square';

    /**
     * @return array<string, TextColor>
     */
    public static function richTextColors(): array
    {
        return [
            'slate' => TextColor::make('Grigio', '#475569', darkColor: '#cbd5e1'),
            'blue' => TextColor::make('Blu', '#2563eb', darkColor: '#60a5fa'),
            'green' => TextColor::make('Verde', '#16a34a', darkColor: '#4ade80'),
            'amber' => TextColor::make('Ambra', '#d97706', darkColor: '#fbbf24'),
            'red' => TextColor::make('Rosso', '#dc2626', darkColor: '#f87171'),
            'purple' => TextColor::make('Viola', '#7c3aed', darkColor: '#a78bfa'),
        ];
    }

    public static function photoDirectory(int|string|null $userId, mixed $noteDate): string
    {
        $normalizedUserId = max(0, (int) $userId);
        $dateString = self::castMixedToString($noteDate);
        $result = preg_replace('/[^0-9-]/', '', $dateString);
        $normalizedDate = (is_string($result) && $result !== '') ? $result : 'draft';

        return "media/daily-notes/{$normalizedUserId}/{$normalizedDate}/photos";
    }

    private static function extractUserId(callable $get): int
    {
        $userId = $get('user_id');
        if (is_numeric($userId)) {
            return (int) $userId;
        }

        $authId = Auth::id();

        return is_numeric($authId) ? (int) $authId : 0;
    }

    private static function castMixedToString(mixed $value): string
    {
        if (is_string($value)) {
            return $value;
        }

        if (is_numeric($value) || is_bool($value) || $value === null) {
            return (string) $value;
        }

        return '';
    }

    private static function isEnabledByPlatform(): bool
    {
        return PlatformSetting::getBool('modules.operator.daily_notes', true);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return self::isEnabledByPlatform() && AccountFeatures::currentPanelAllowsFeature(
            Auth::user(),
            adminFeature: null,
            operatorFeature: 'operator.daily_notes',
            adminDefault: false,
            operatorDefault: true,
            fallback: false,
        );
    }

    public static function canViewAny(): bool
    {
        return self::isEnabledByPlatform() && AccountFeatures::currentPanelAllowsFeature(
            Auth::user(),
            adminFeature: null,
            operatorFeature: 'operator.daily_notes',
            adminDefault: false,
            operatorDefault: true,
            fallback: false,
        )
            && parent::canViewAny();
    }

    public static function canCreate(): bool
    {
        return self::isEnabledByPlatform() && AccountFeatures::currentPanelAllowsFeature(
            Auth::user(),
            adminFeature: null,
            operatorFeature: 'operator.daily_notes',
            adminDefault: false,
            operatorDefault: true,
            fallback: false,
        )
            && parent::canCreate();
    }

    public static function canEdit(Model $record): bool
    {
        return self::isEnabledByPlatform() && AccountFeatures::currentPanelAllowsFeature(
            Auth::user(),
            adminFeature: null,
            operatorFeature: 'operator.daily_notes',
            adminDefault: false,
            operatorDefault: true,
            fallback: false,
        )
            && parent::canEdit($record);
    }

    public static function canView(Model $record): bool
    {
        return self::isEnabledByPlatform() && AccountFeatures::currentPanelAllowsFeature(
            Auth::user(),
            adminFeature: null,
            operatorFeature: 'operator.daily_notes',
            adminDefault: false,
            operatorDefault: true,
            fallback: false,
        )
            && parent::canView($record);
    }

    public static function getNavigationGroup(): string
    {
        return __('interventions.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('interventions.daily_note');
    }

    public static function getPluralModelLabel(): string
    {
        return __('interventions.daily_notes');
    }

    /**
     * @return array<string, Section>
     */
    public function getInfolistSchema(): array
    {
        /** @var view-string $dailyNoteView */
        $dailyNoteView = 'intervention::filament.infolists.entries.daily-note-display';

        return [
            'main' => Section::make()
                ->columnSpanFull()
                ->components([
                    Grid::make(2)->components([
                        TextEntry::make('user.name'),
                        TextEntry::make('note_date')

                            ->date(),
                    ]),
                    ViewEntry::make('daily_note_display')
                        ->hiddenLabel()
                        ->view($dailyNoteView)
                        ->columnSpanFull(),
                ])
                ->columns(2),
        ];
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
            'index' => Pages\ListDailyNotes::route('/'),
            'create' => Pages\CreateDailyNote::route('/create'),
            'view' => Pages\ViewDailyNote::route('/{record}'),
            'edit' => Pages\EditDailyNote::route('/{record}/edit'),
        ];
    }
}
