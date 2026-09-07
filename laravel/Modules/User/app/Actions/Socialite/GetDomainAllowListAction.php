<?php

/**
 * @see https://github.com/DutchCodingCompany/filament-socialite
 */

declare(strict_types=1);

namespace Modules\User\Actions\Socialite;

<<<<<<< HEAD
<<<<<<< HEAD
=======
use Exception;
>>>>>>> f548be94 (.)
=======
use Exception;
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Illuminate\Support\Arr;
use Spatie\QueueableAction\QueueableAction;

class GetDomainAllowListAction
{
    use QueueableAction;

<<<<<<< HEAD
<<<<<<< HEAD
    public function __construct(
        private readonly Arr $arrHelper,
    ) {}

    /**
     * Execute the action.
     *
     * @return array<int, string>
     */
    public function execute(): array
    {
        $res = config('socialite.domain_allowlist', []);
        if (\is_string($res)) {
            return $this->arrHelper->wrap($res);
        }

        if (\is_array($res)) {
            return array_values(array_filter(array_map(
                static fn (mixed $item): ?string => \is_scalar($item) || $item instanceof \Stringable ? (string) $item : null,
                $res
            ), static fn (?string $item): bool => null !== $item));
        }

        return [];
=======
=======
>>>>>>> 87273113 (.)
    /**
     * Execute the action.
     */
    public function execute(): array
    {
        $res = config('filament-socialite.domain_allowlist');
        if (\is_string($res)) {
            return Arr::wrap($res);
        }

        if (\is_array($res)) {
            return $res;
        }

        throw new Exception('check config filament-socialite.domain_allowlist');
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    public function __construct(
        private readonly Arr $arrHelper,
    ) {}

    /**
     * Execute the action.
     *
     * @return array<int, string>
     */
    public function execute(): array
    {
        $res = config('socialite.domain_allowlist', []);
        if (\is_string($res)) {
            return $this->arrHelper->wrap($res);
        }

        if (\is_array($res)) {
            return array_values(array_filter(array_map(
                static fn (mixed $item): ?string => \is_scalar($item) || $item instanceof \Stringable ? (string) $item : null,
                $res
            ), static fn (?string $item): bool => null !== $item));
        }

        return [];
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }
}
