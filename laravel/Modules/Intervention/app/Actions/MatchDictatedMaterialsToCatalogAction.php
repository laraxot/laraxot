<?php

declare(strict_types=1);

namespace Modules\Intervention\Actions;

use Modules\Catalog\Models\Product;
use Modules\Intervention\Data\DictatedMaterialLine;
use Spatie\QueueableAction\QueueableAction;

use function Safe\preg_split;

/**
 * Matches voice-dictated materials against the catalog so the extra materials
 * carry a billing price. Unmatched lines are left free (text only) for the
 * operator to price manually.
 */
final class MatchDictatedMaterialsToCatalogAction
{
    use QueueableAction;

    /**
     * @param  list<DictatedMaterialLine>  $materials
     * @return list<DictatedMaterialLine>
     */
    public function execute(array $materials): array
    {
        return array_values(array_map(
            fn (DictatedMaterialLine $line): DictatedMaterialLine => $this->matchLine($line),
            $materials,
        ));
    }

    private function matchLine(DictatedMaterialLine $line): DictatedMaterialLine
    {
        if ($line->isMatched()) {
            return $line;
        }

        $needle = trim(mb_strtolower($line->description));
        if ($needle === '') {
            return $line;
        }

        $product = $this->findProduct($needle);
        if ($product === null) {
            return $line;
        }

        $rawUnitPrice = Product::query()->whereKey($product->id)->value('unit_price');
        $unitPrice = is_numeric($rawUnitPrice) ? (float) $rawUnitPrice : null;

        return $line->withCatalogMatch((int) $product->id, $unitPrice);
    }

    private function findProduct(string $needle): ?Product
    {
        // 1. Exact (case-insensitive).
        $exact = Product::query()
            ->where('is_active', true)
            ->whereRaw('LOWER(name) = ?', [$needle])
            ->orderBy('id')
            ->first();

        if ($exact !== null) {
            return $exact;
        }

        // 2. The dictated text contains a catalog name (partial).
        $partial = $this->firstProductLike($needle);
        if ($partial !== null) {
            return $partial;
        }

        // 3. Similar match: the most significant word of what was dictated
        //    (e.g. "valvola a sfera da mezzo pollice" -> "Valvola a sfera").
        $keyword = $this->longestWord($needle);

        return $keyword !== '' ? $this->firstProductLike($keyword) : null;
    }

    private function firstProductLike(string $term): ?Product
    {
        $escaped = addcslashes($term, '%_\\');

        return Product::query()
            ->where('is_active', true)
            ->whereRaw('LOWER(name) LIKE ?', ['%'.$escaped.'%'])
            ->orderByRaw('LENGTH(name)')
            ->orderBy('id')
            ->first();
    }

    private function longestWord(string $text): string
    {
        $words = preg_split('/\s+/', $text) ?: [];
        $best = '';
        foreach ($words as $word) {
            $word = is_string($word) ? $word : '';
            if (mb_strlen($word) > mb_strlen($best)) {
                $best = $word;
            }
        }

        return mb_strlen($best) >= 4 ? $best : '';
    }
}
