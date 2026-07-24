<?php

declare(strict_types=1);

namespace Modules\Customer\Support;

use Modules\Customer\Models\Customer;

use function Safe\preg_split;

final class CustomerSimilarity
{
    public static function find(string $name, float $threshold = 80.0): ?Customer
    {
        $needle = mb_strtolower(trim($name));
        if ($needle === '') {
            return null;
        }

        $exact = Customer::query()->whereRaw('LOWER(legal_name) = ?', [$needle])->orderBy('id')->first();
        if ($exact instanceof Customer) {
            return $exact;
        }

        $contains = Customer::query()
            ->whereRaw('LOWER(legal_name) LIKE ?', ['%'.self::escape($needle).'%'])
            ->orderByDesc('id')
            ->first();
        if ($contains instanceof Customer) {
            return $contains;
        }

        $words = array_values(array_filter(
            preg_split('/\s+/', $needle) ?: [],
            static fn (mixed $word): bool => is_string($word) && mb_strlen($word) >= 4,
        ));
        if ($words === []) {
            return null;
        }

        $query = Customer::query();
        foreach ($words as $word) {
            if (! is_string($word)) {
                continue;
            }
            $query->orWhereRaw('LOWER(legal_name) LIKE ?', ['%'.self::escape($word).'%']);
        }

        $best = null;
        $bestScore = 0.0;
        foreach ($query->limit(300)->get() as $customer) {
            $percent = 0.0;
            similar_text($needle, mb_strtolower((string) $customer->legal_name), $percent);
            if ($percent > $bestScore) {
                $bestScore = $percent;
                $best = $customer;
            }
        }

        return $bestScore >= $threshold ? $best : null;
    }

    private static function escape(string $value): string
    {
        return addcslashes($value, '%_\\');
    }
}
