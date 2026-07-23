<?php

declare(strict_types=1);

namespace Modules\Quotation\Actions;

use Modules\Catalog\Actions\LinkMetelItemToProductAction;
use Modules\Catalog\Models\MetelCatalogItem;
use Modules\Catalog\Models\Product;
use Modules\Quotation\Domain\DTO\DictatedQuotationItem;

/**
 * Matches voice-dictated quotation items against the products catalog (fed by the
 * listino / magazzino / METEL imports) so each line carries a price. Unmatched
 * lines stay free (text only) for the operator to price manually.
 */
final class MatchDictatedItemsToCatalogAction
{
    /**
     * @param  list<DictatedQuotationItem>  $items
     * @return list<DictatedQuotationItem>
     */
    public function execute(array $items): array
    {
        return array_values(array_map(
            fn (DictatedQuotationItem $item): DictatedQuotationItem => $this->matchItem($item),
            $items,
        ));
    }

    private function matchItem(DictatedQuotationItem $item): DictatedQuotationItem
    {
        if ($item->isMatched()) {
            return $item;
        }

        $needle = trim(mb_strtolower($item->description));
        if ($needle === '') {
            return $item;
        }

        $product = $this->findProduct($needle);
        if ($product === null) {
            return $item;
        }

        $unitPrice = $product->unit_price !== null ? (float) $product->unit_price : null;

        // Use the exact catalog article (name + price), so a matched line clearly
        // shows the real product from the listino/magazzino — not the dictated text.
        return new DictatedQuotationItem(
            description: is_string($product->name) && $product->name !== '' ? $product->name : $item->description,
            quantity: $item->quantity,
            unitPrice: $unitPrice ?? $item->unitPrice,
            matchedProductId: (int) $product->id,
        );
    }

    private function findProduct(string $needle): ?Product
    {
        // 1. Exact (case-insensitive) name or SKU.
        $exact = Product::query()
            ->where('is_active', true)
            ->where(function ($query) use ($needle): void {
                $query->whereRaw('LOWER(name) = ?', [$needle])
                    ->orWhereRaw('LOWER(sku) = ?', [$needle]);
            })
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

        // 3. Similar match on the most significant dictated word.
        $keyword = $this->longestWord($needle);
        $byKeyword = $keyword !== '' ? $this->firstProductLike($keyword) : null;
        if ($byKeyword !== null) {
            return $byKeyword;
        }

        // 4. Fuzzy match: tolerate typos/near-misses (e.g. "scaldavagno" -> "scaldabagno").
        $fuzzy = $this->findFuzzyProduct($keyword);
        if ($fuzzy !== null) {
            return $fuzzy;
        }

        // 5. Fall back to the imported METEL catalog: promote the matched article
        //    to a product so the quotation line carries its listino price.
        return $this->findViaMetel($needle, $keyword);
    }

    /**
     * Best catalog product whose name contains a word very similar to the dictated
     * keyword (typo-tolerant). Returns null when nothing is close enough.
     */
    private function findFuzzyProduct(string $keyword): ?Product
    {
        if (mb_strlen($keyword) < 4) {
            return null;
        }

        $prefix = addcslashes(mb_substr($keyword, 0, 3), '%_\\');
        $candidates = Product::query()
            ->where('is_active', true)
            ->whereRaw('LOWER(name) LIKE ?', ['%'.$prefix.'%'])
            ->orderByRaw('LENGTH(name)')
            ->limit(300)
            ->get();

        $best = null;
        $bestScore = 0.0;
        foreach ($candidates as $product) {
            foreach (preg_split('/\s+/', mb_strtolower((string) $product->name)) ?: [] as $word) {
                if (mb_strlen($word) < 4) {
                    continue;
                }
                $percent = 0.0;
                similar_text($keyword, $word, $percent);
                if ($percent > $bestScore) {
                    $bestScore = $percent;
                    $best = $product;
                }
            }
        }

        return $bestScore >= 80.0 ? $best : null;
    }

    private function findViaMetel(string $needle, string $keyword): ?Product
    {
        $escaped = addcslashes($needle, '%_\\');
        $item = MetelCatalogItem::query()
            ->whereRaw('LOWER(metel_code) = ?', [$needle])
            ->orWhereRaw('LOWER(description) LIKE ?', ['%'.$escaped.'%'])
            ->orderByRaw('LENGTH(description)')
            ->orderBy('id')
            ->first();

        if ($item === null && $keyword !== '') {
            $item = MetelCatalogItem::query()
                ->whereRaw('LOWER(description) LIKE ?', ['%'.addcslashes($keyword, '%_\\').'%'])
                ->orderByRaw('LENGTH(description)')
                ->orderBy('id')
                ->first();
        }

        if ($item === null) {
            return null;
        }

        return app(LinkMetelItemToProductAction::class)->execute($item);
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
            if (mb_strlen($word) > mb_strlen($best)) {
                $best = $word;
            }
        }

        return mb_strlen($best) >= 4 ? $best : '';
    }
}
