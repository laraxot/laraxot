<?php

declare(strict_types=1);

namespace Modules\Timber\Support;

use Modules\Timber\Models\TimberInvoice;

final class TimberInvoiceNumberGenerator
{
    /**
     * Next sequential number for a fiscal document kind in the current year,
     * formatted as PREFIX-YYYY-NNNN (e.g. FT-2026-0001).
     */
    public function next(string $kind): string
    {
        $year = now()->format('Y');
        $prefix = $this->prefixFor($kind);

        $sequence = TimberInvoice::query()
            ->withTrashed()
            ->where('kind', $kind)
            ->whereNotNull('number')
            ->whereYear('invoice_date', $year)
            ->count() + 1;

        do {
            $number = sprintf('%s-%s-%04d', $prefix, $year, $sequence);
            $sequence++;
        } while (TimberInvoice::query()
            ->withTrashed()
            ->where('kind', $kind)
            ->where('number', $number)
            ->exists());

        return $number;
    }

    private function prefixFor(string $kind): string
    {
        $prefixes = config('timber.config.invoice_number_prefixes', []);

        if (is_array($prefixes) && isset($prefixes[$kind]) && is_string($prefixes[$kind])) {
            return $prefixes[$kind];
        }

        return 'DOC';
    }
}
