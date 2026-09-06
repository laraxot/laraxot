<?php

declare(strict_types=1);

use Modules\Intervention\Models\Intervention;
use Modules\Signature\Filament\Pages\Concerns\InteractsWithCanvasFormData;

/**
 * Thin harness exposing the private scalar-narrowing helpers of
 * InteractsWithCanvasFormData as public methods, so they can be unit
 * tested without booting a Filament page (Auth, Livewire, DB).
 *
 * These helpers exist to fix a batch of PHPStan `cast.int` / `cast.string`
 * errors: Filament form state is an untyped `array<string, mixed>`, and the
 * fix narrows that `mixed` payload to concrete scalars instead of blindly
 * casting it.
 */
final class CanvasFormDataCoercionHarness
{
    use InteractsWithCanvasFormData;

    public ?Intervention $intervention = null;

    /**
     * @return array<string, mixed>
     */
    public function publicExtractStringKeyedArray(mixed $value): array
    {
        return $this->extractStringKeyedArray($value);
    }

    public function publicCoerceToInt(mixed $value, int $default = 0): int
    {
        return $this->coerceToInt($value, $default);
    }

    public function publicCoerceToString(mixed $value): string
    {
        return $this->coerceToString($value);
    }
}

it('extracts a string-keyed array from array input, discarding non-array input', function (): void {
    $harness = new CanvasFormDataCoercionHarness();

    expect($harness->publicExtractStringKeyedArray(['a' => 1, 2 => 'b']))
        ->toBe(['a' => 1, '2' => 'b']);

    expect($harness->publicExtractStringKeyedArray('not-an-array'))->toBe([]);
    expect($harness->publicExtractStringKeyedArray(null))->toBe([]);
});

it('coerces mixed form values to int without blindly casting unknown types', function (): void {
    $harness = new CanvasFormDataCoercionHarness();

    expect($harness->publicCoerceToInt(42))->toBe(42);
    expect($harness->publicCoerceToInt('42'))->toBe(42);
    expect($harness->publicCoerceToInt('not-numeric'))->toBe(0);
    expect($harness->publicCoerceToInt(null, 7))->toBe(7);
    expect($harness->publicCoerceToInt([1, 2, 3], 9))->toBe(9);
});

it('coerces mixed form values to string without blindly casting unknown types', function (): void {
    $harness = new CanvasFormDataCoercionHarness();

    expect($harness->publicCoerceToString('hello'))->toBe('hello');
    expect($harness->publicCoerceToString(42))->toBe('42');
    expect($harness->publicCoerceToString(3.5))->toBe('3.5');
    expect($harness->publicCoerceToString(true))->toBe('1');
    expect($harness->publicCoerceToString(null))->toBe('');
    expect($harness->publicCoerceToString([1, 2]))->toBe('');
});
