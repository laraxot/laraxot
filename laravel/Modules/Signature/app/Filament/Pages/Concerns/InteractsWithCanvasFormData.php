<?php

declare(strict_types=1);

namespace Modules\Signature\Filament\Pages\Concerns;

use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Modules\User\Models\User;

/**
 * Shared helpers for the canvas capture pages (signature / sketch).
 *
 * The Filament form state (`$this->form->getState()`) is an untyped
 * `array<string, mixed>` by nature (it comes from client-submitted, dynamic
 * form data), so these helpers narrow that `mixed` payload to concrete
 * scalar types at the boundary instead of relying on blind casts.
 *
 * Host classes must declare `public ?Intervention $intervention = null;`.
 */
trait InteractsWithCanvasFormData
{
    /**
     * @return array<string, mixed>
     */
    private function extractStringKeyedArray(mixed $value): array
    {
        if (! is_array($value)) {
            return [];
        }

        $normalized = [];

        foreach ($value as $key => $item) {
            $normalized[(string) $key] = $item;
        }

        return $normalized;
    }

    /**
     * Narrow a `mixed` form-state value to an `int`, without blindly
     * casting values whose real type is unknown to PHPStan.
     */
    private function coerceToInt(mixed $value, int $default = 0): int
    {
        if (is_int($value)) {
            return $value;
        }

        if (is_string($value) && is_numeric($value)) {
            return (int) $value;
        }

        return $default;
    }

    /**
     * Narrow a `mixed` form-state value to a `string`, without blindly
     * casting values whose real type is unknown to PHPStan.
     */
    private function coerceToString(mixed $value): string
    {
        if (is_string($value)) {
            return $value;
        }

        if (is_int($value) || is_float($value) || is_bool($value)) {
            return (string) $value;
        }

        if ($value instanceof \Stringable) {
            return (string) $value;
        }

        return '';
    }

    /**
     * @return array<int, string>
     */
    private function resolveAllowedUserOptions(): array
    {
        $currentUser = Auth::user();
        $currentUserId = Auth::id();

        if ($currentUserId === null) {
            return [];
        }

        if (request()->is('operator/*')) {
            return User::query()
                ->whereKey($currentUserId)
                ->get(['id', 'name'])
                ->mapWithKeys(static fn (User $user): array => [(int) $user->id => (string) $user->name])
                ->all();
        }

        $workOrderId = $this->intervention?->work_order_id ?? 0;

        if ($workOrderId > 0 && AccountFeatures::hasAssignedPerimeter($currentUser)) {
            return User::query()
                ->whereIn('id', function (QueryBuilder $query) use ($workOrderId): void {
                    $query->select('user_id')
                        ->from('work_order_assignments')
                        ->where('work_order_id', $workOrderId);
                })
                ->orderBy('name')
                ->get(['id', 'name'])
                ->mapWithKeys(static fn (User $user): array => [(int) $user->id => (string) $user->name])
                ->all();
        }

        return User::query()
            ->orderBy('name')
            ->get(['id', 'name'])
            ->mapWithKeys(static fn (User $user): array => [(int) $user->id => (string) $user->name])
            ->all();
    }
}
