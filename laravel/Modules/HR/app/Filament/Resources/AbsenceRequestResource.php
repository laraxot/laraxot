<?php

declare(strict_types=1);

namespace Modules\HR\Filament\Resources;

use App\Support\AccountFeatures;
use App\Support\Concerns\HasAccountFeatureVisibility;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Modules\HR\Filament\Resources\AbsenceRequestResource\Pages;
use Modules\HR\Models\AbsenceRequest;
use Modules\Xot\Filament\Resources\XotBaseResource;

final class AbsenceRequestResource extends XotBaseResource
{
    use HasAccountFeatureVisibility;

    /** @var array<string, string> */
    private const array ABSENCE_TYPE_ALIASES = [
        'permesso' => 'leave',
        'ferie' => 'vacation',
        'malattia' => 'sick',
        'infortunio' => 'injury',
    ];

    protected static ?string $model = AbsenceRequest::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calendar';

    private const string ACCOUNT_FEATURE_ADMIN = 'admin.hr';

    private const string ACCOUNT_FEATURE_OPERATOR = 'operator.hr';

    public static function getNavigationGroup(): string
    {
        return __('hr.navigation');
    }

    protected static function isAvailableInCurrentPanel(): bool
    {
        return AccountFeatures::currentPanelAllowsFeature(
            Auth::user(),
            adminFeature: self::ACCOUNT_FEATURE_ADMIN,
            operatorFeature: self::ACCOUNT_FEATURE_OPERATOR,
        );
    }

    public static function getModelLabel(): string
    {
        return __('hr.absence_request');
    }

    public static function getPluralModelLabel(): string
    {
        return __('hr.absence_requests');
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

    public static function translateAbsenceType(?string $type): string
    {
        if ($type === null || trim($type) === '') {
            return '-';
        }

        $normalized = self::normalizeAbsenceType($type);
        if ($normalized === null || trim($normalized) === '') {
            return '-';
        }

        // loadTranslationsFrom(..., 'hr') + file lang/{locale}/hr.php → hr::hr.*
        $key = "hr::hr.absence_types.{$normalized}";

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
