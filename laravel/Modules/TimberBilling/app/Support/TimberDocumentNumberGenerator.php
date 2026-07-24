<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Support;

use Modules\TimberBilling\Models\TimberDocument;

final class TimberDocumentNumberGenerator
{
    /**
     * Next sequential document number for a type in the current year,
     * formatted as PREFIX-YYYY-NNNN (e.g. DDT-2026-0001).
     */
    public function next(string $documentTypeCode): string
    {
        $year = now()->format('Y');
        $prefix = $this->prefixFor($documentTypeCode);

        $sequence = TimberDocument::query()
            ->withTrashed()
            ->where('document_type_code', $documentTypeCode)
            ->whereYear('document_date', $year)
            ->count() + 1;

        do {
            $number = sprintf('%s-%s-%04d', $prefix, $year, $sequence);
            $sequence++;
        } while (TimberDocument::query()
            ->withTrashed()
            ->where('document_type_code', $documentTypeCode)
            ->where('number', $number)
            ->exists());

        return $number;
    }

    private function prefixFor(string $documentTypeCode): string
    {
        $prefixes = config('timberbilling.timber.document_number_prefixes', []);

        if (is_array($prefixes) && isset($prefixes[$documentTypeCode]) && is_string($prefixes[$documentTypeCode])) {
            return $prefixes[$documentTypeCode];
        }

        return 'DOC';
    }
}
