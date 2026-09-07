<?php

declare(strict_types=1);

namespace Modules\MetalWork\Support;

use Modules\MetalWork\Datas\SupplierInvoice;
use Modules\MetalWork\Datas\SupplierInvoiceLine;

use function Safe\preg_match;
use function Safe\preg_match_all;
use function Safe\preg_replace;
use function Safe\preg_split;

/**
 * Reads the raw-material rows out of the text of an Italian electronic invoice
 * (the human-readable rendering of a FatturaPA XML).
 *
 * The two suppliers lay their rows out differently — one puts the article code
 * on its own `Rif. testo:` line, the other prefixes the description with
 * `CODE (FORN)` — so the anchor is the amounts group instead: quantity, unit
 * price, unit of measure, VAT, total. Requiring a unit of measure is also what
 * keeps carriage and collection fees out: those rows carry no UM.
 */
final class SupplierInvoiceParser
{
    /**
     * Quantity, unit price and unit of measure, then optionally the discount and
     * the VAT/total pair. Italian decimal commas throughout.
     *
     * Requiring a unit of measure is what keeps carriage and collection fees out
     * of the price list: those rows carry none. The trailing pair is optional
     * because a discounted row pushes it onto the next rendered line.
     */
    /** @var string */
    private const string AMOUNTS_PATTERN = '/(\d{1,3}(?:\.\d{3})*,\d+)\s+(\d{1,3}(?:\.\d{3})*,\d+)\s*(KG|ML|MT|MQ|NR|PZ)\b(?:\s*-?\s*(\d+[.,]\d+)\s*%)?(?:\s+(\d{1,2},\d{2})\s+(\d{1,3}(?:\.\d{3})*,\d+))?/u';

    /** The table header ends here; rows above it belong to the invoice preamble. */
    /** @var string */
    private const string ITEMS_START_MARKER = 'Prezzo totale';

    /**
     * A line above the row that can only be part of the supplier's article code:
     * a single uppercase token, optionally followed by the "(FORN)" marker, which
     * the renderer often pushes onto its own line. Deliberately narrow — the notes
     * printed under a row ("4 PZ. COD. ART. OSS-04418460 - 6") are also uppercase,
     * and must not be mistaken for the next row's code.
     */
    /** @var string */
    private const string CODE_FRAGMENT_PATTERN = '/^(?:[A-Z0-9_.\-]{1,20})?\s*(?:\(FORN\))?$/u';

    /** Ordered: the first family that matches wins, so "TUBO QUADRO" is a tube. */
    /** @var array<string, string> */
    private const array FAMILY_RULES = [
        'service' => '/\b(TAGLI[OA]?|FASCE|SPESE|TRASPORTO|INCASSO|SPEDIZIONE)\b/u',
        'oxy_cut' => '/\b(OSSITAGL|SAGOMAT|PIASTRE)/u',
        'mesh' => '/\bRETE\b/u',
        'tube' => '/\bTUBO|TUBOLARE\b/u',
        'beam' => '/\b(TRAVI|TRAVE|HE-?A|HE-?B|IPE|UPN)\b/u',
        'flat' => '/\bPIATTO\b/u',
        'angle' => '/\b(ANGOLARE|FERRO L\b)/u',
        'round' => '/\bTONDO\b/u',
        'square' => '/\bQUADRO\b/u',
        'sheet' => '/\b(LAMIER|ZINCATE|BUGNATE|COR-?TEN|DECAPAT)/u',
    ];

    public function parse(string $text, string $sourceFile): SupplierInvoice
    {
        /** @var list<string> $lines */
        $lines = preg_split('/\R/u', $this->joinBrokenDiscounts($text));

        return new SupplierInvoice(
            supplierName: $this->supplierName($lines),
            invoiceNumber: $this->invoiceNumber($text),
            issuedOn: $this->issuedOn($text),
            sourceFile: $sourceFile,
            lines: $this->itemLines($lines),
        );
    }

    /**
     * The renderer breaks the discount column across three lines ("-", "10.00",
     * "%"), which splits the amounts group of the row it belongs to. Putting it
     * back inline is the only way the row can be read as one.
     */
    private function joinBrokenDiscounts(string $text): string
    {
        return (string) preg_replace('/\s*-\s*\R\s*(\d+[.,]\d+)\s*\R\s*%/u', ' -$1% ', $text);
    }

    /**
     * The seller is the first `Denominazione:` — the buyer's block follows it.
     *
     * @param  list<string>  $lines
     */
    private function supplierName(array $lines): string
    {
        foreach ($lines as $line) {
            if (preg_match('/^\s*Denominazione:\s*(\S.*)$/u', $line, $match) === 1) {
                return trim(($match[1] ?? ''));
            }
        }

        return '';
    }

    private function invoiceNumber(string $text): ?string
    {
        // The document line is "<numero> <data> <codice destinatario>".
        return preg_match('/^\s*([0-9A-Z]{5,})\s+(\d{2}-\d{2}-\d{4})\s+\S+\s*$/mu', $text, $match) === 1
            ? ($match[1] ?? '')
            : null;
    }

    private function issuedOn(string $text): ?string
    {
        if (preg_match('/^\s*[0-9A-Z]{5,}\s+(\d{2})-(\d{2})-(\d{4})\s+\S+\s*$/mu', $text, $match) !== 1) {
            return null;
        }

        return sprintf('%s-%s-%s', ($match[3] ?? ''), ($match[2] ?? ''), ($match[1] ?? ''));
    }

    /**
     * @param  list<string>  $lines
     * @return list<SupplierInvoiceLine>
     */
    private function itemLines(array $lines): array
    {
        $items = [];
        $pending = [];
        $pendingCode = null;
        $deliveryNote = null;
        $nextReferenceIsArticleCode = false;
        $started = false;

        foreach ($lines as $rawLine) {
            $line = trim(str_replace("\t", ' ', $rawLine));

            if ($line === '') {
                continue;
            }

            if (! $started) {
                $started = str_contains($line, self::ITEMS_START_MARKER);

                continue;
            }

            if (str_contains($line, 'RIEPILOGHI IVA E TOTALI')) {
                break;
            }

            if (preg_match('/\bDDT\s+([0-9]+)\s+del\b/u', $line, $match) === 1) {
                $deliveryNote = ($match[1] ?? '');
            }

            // "Tipo dato: ARTICOLO:" announces that the next "Rif. testo:" is the
            // supplier's article code; any other "Tipo dato" announces a free note.
            if (preg_match('/^Tipo dato:/u', $line) === 1) {
                $nextReferenceIsArticleCode = str_contains($line, 'ARTICOLO');

                continue;
            }

            if (preg_match('/^Rif\.\s*testo:\s*(\S.*)$/u', $line, $match) === 1) {
                if ($nextReferenceIsArticleCode) {
                    $pendingCode = trim(($match[1] ?? ''));
                }

                $nextReferenceIsArticleCode = false;

                continue;
            }

            if ($this->isContextLine($line)) {
                continue;
            }

            $amounts = $this->lastAmountsMatch($line);

            if ($amounts === null) {
                $pending[] = $line;

                continue;
            }

            $item = $this->buildLine(
                candidate: $this->candidateFor($pending, $amounts['prefix']),
                explicitCode: $pendingCode,
                amounts: $amounts,
                deliveryNote: $deliveryNote,
            );

            if ($item !== null) {
                $items[] = $item;
            }

            $pending = [];
            $pendingCode = null;
        }

        return $items;
    }

    /**
     * The amounts sit at the end of the row, so the last group on the line is the
     * right one: an earlier one belongs to the description ("L.6000 12,00").
     *
     * @return array{prefix: string, quantity: float, unitPrice: float, unitCode: string, discountPercent: float, lineTotal: float|null}|null
     */
    private function lastAmountsMatch(string $line): ?array
    {
        if (preg_match_all(self::AMOUNTS_PATTERN, $line, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE) === 0) {
            return null;
        }

        $match = $matches[count($matches) - 1];
        $offset = $match[0][1];

        // A row with no discount but a trailing total still fills group 4 as a
        // non-participating group; its offset is -1, which is what distinguishes
        // it from a real capture.
        $discount = $this->captured($match, 4);
        $total = $this->captured($match, 6);

        return [
            'prefix' => trim(substr($line, 0, $offset)),
            'quantity' => $this->decimal($match[1][0]),
            'unitPrice' => $this->decimal($match[2][0]),
            'unitCode' => mb_strtolower($match[3][0]),
            'discountPercent' => $discount === null ? 0.0 : (float) str_replace(',', '.', $discount),
            'lineTotal' => $total === null ? null : $this->decimal($total),
        ];
    }

    /**
     * The text of one capture group, or null when the group did not participate.
     *
     * @param  array<int, array{string, int}>  $match
     */
    private function captured(array $match, int $group): ?string
    {
        return isset($match[$group]) && $match[$group][1] !== -1 ? $match[$group][0] : null;
    }

    /**
     * The text the description and the article code are read from.
     *
     * When the row carries its description inline, the lines above it are either
     * the article code split across two lines ("LCOR15003000" / "20 (FORN)") or a
     * note belonging to the previous row. Only the code-shaped ones are kept, so a
     * note never leaks into the next row's description.
     *
     * @param  list<string>  $pending
     */
    private function candidateFor(array $pending, string $prefix): string
    {
        if ($prefix === '') {
            return trim(implode(' ', $pending));
        }

        $codeFragments = [];

        foreach (array_slice($pending, -2) as $line) {
            if (preg_match(self::CODE_FRAGMENT_PATTERN, $line) === 1) {
                $codeFragments[] = $line;
            }
        }

        return trim(implode(' ', [...$codeFragments, $prefix]));
    }

    /** Order and delivery-note references sit between rows and describe neither. */
    private function isContextLine(string $line): bool
    {
        return preg_match('/^(Rif\.|RIF\.|DDT\s|Causale|Cod\.|articolo$|Descrizione)/u', $line) === 1;
    }

    /**
     * @param  array{prefix: string, quantity: float, unitPrice: float, unitCode: string, discountPercent: float, lineTotal: float|null}  $amounts
     */
    private function buildLine(
        string $candidate,
        ?string $explicitCode,
        array $amounts,
        ?string $deliveryNote,
    ): ?SupplierInvoiceLine {
        $description = $candidate;
        $code = $explicitCode;

        // The other layout writes "OSSITAGLIO (FORN)" ahead of the description,
        // and the renderer sometimes splits that code across two lines.
        if (preg_match('/^([A-Z0-9_.\- ]+?)\s*\(FORN\)\s*/u', $candidate, $match) === 1) {
            $code ??= trim(($match[1] ?? ''));
            $description = trim(mb_substr($candidate, mb_strlen(($match[0] ?? ''))));
        }

        $description = trim((string) preg_replace('/\s+/u', ' ', $description));

        // A discount on the row is part of what the material actually cost.
        $unitPrice = round($amounts['unitPrice'] * (1 - $amounts['discountPercent'] / 100), 4);

        if ($description === '' || $unitPrice <= 0.0) {
            return null;
        }

        $family = $this->familyFor($description);

        // Services are billed on the invoice but are not stock: they belong to a
        // work centre, not to the material price list.
        if ($family === 'service') {
            return null;
        }

        return new SupplierInvoiceLine(
            code: $code ?? $this->derivedCode($description),
            description: $description,
            familyCode: $family,
            unitCode: $amounts['unitCode'],
            quantity: $amounts['quantity'],
            unitPrice: $unitPrice,
            lineTotal: $amounts['lineTotal'] ?? round($amounts['quantity'] * $unitPrice, 2),
            steelGrade: $this->steelGrade($description),
            deliveryNoteNumber: $deliveryNote,
        );
    }

    private function familyFor(string $description): string
    {
        $upper = mb_strtoupper($description);

        foreach (self::FAMILY_RULES as $family => $pattern) {
            if (preg_match($pattern, $upper) === 1) {
                return $family;
            }
        }

        return 'other';
    }

    /**
     * S235 JR, S275JR, S355J2+N, S355JOWP, DX 51 — the grades the customer buys.
     * Written with and without the space depending on who typed the row.
     */
    private function steelGrade(string $description): ?string
    {
        $upper = mb_strtoupper($description);

        if (preg_match('/\bS\s?(\d{3})\s?([A-Z0-9+]{0,6})\b/u', $upper, $match) === 1) {
            return trim('S'.($match[1] ?? '').($match[2] ?? ''));
        }

        if (preg_match('/\bDX\s?(\d{2})\b/u', $upper, $match) === 1) {
            return 'DX'.($match[1] ?? '');
        }

        return null;
    }

    /** A row without a supplier code still needs a stable key: derive it. */
    private function derivedCode(string $description): string
    {
        $slug = (string) preg_replace('/[^A-Z0-9]+/u', '-', mb_strtoupper($description));

        return trim(mb_substr($slug, 0, 60), '-');
    }

    private function decimal(string $value): float
    {
        return (float) str_replace(',', '.', str_replace('.', '', $value));
    }
}
