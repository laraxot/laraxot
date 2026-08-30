<?php

declare(strict_types=1);

namespace Modules\Signage\Actions;

use Modules\Signage\Datas\SignQuotation;
use Modules\Signage\Datas\SignQuotationLine;
use Modules\Signage\Models\SignComponent;
use Modules\Signage\Models\SignEstimationRule;
use Modules\Signage\Models\SignPriceListItem;
use Modules\Signage\Models\SignSpec;

use function Safe\preg_split;

/**
 * Turns a sign spec into a priced quotation: matches each component to a
 * price-list item, works out (or accepts) a quantity, and rolls up the totals.
 *
 * Quantities that follow from the drawing geometry (sheet area in m²) are
 * estimated automatically; quantities that don't (milling metres, labour
 * minutes) are left for the operator and flagged as `needsInput` — the action
 * never invents them.
 */
final class PriceSignSpecAction
{
    /** @var array<string, SignEstimationRule> */
    private array $rules = [];

    /**
     * @param array{
     *     markup_percent?: float,
     *     quantities?: array<string, float>,
     *     item_overrides?: array<string, string>,
     *     persist?: bool
     * } $options
     */
    public function execute(SignSpec $spec, array $options = []): SignQuotation
    {
        $spec->loadMissing('components');

        /** @var array<string, SignEstimationRule> $rules */
        $rules = SignEstimationRule::query()->where('is_active', true)->get()->keyBy('role_code')->all();
        $this->rules = $rules;

        /** @var array<string, ?string> $roleToCategory */
        $roleToCategory = config('signage.role_to_category', []);
        $defaultMarkup = config('signage.default_markup_percent', 0);
        $markup = $options['markup_percent'] ?? (is_numeric($defaultMarkup) ? (float) $defaultMarkup : 0.0);
        $quantities = $options['quantities'] ?? [];
        $overrides = $options['item_overrides'] ?? [];
        $persist = $options['persist'] ?? false;

        $lines = [];
        $totalCost = 0.0;
        $hasMissing = false;

        foreach ($spec->components as $component) {
            $category = $roleToCategory[$component->role_code] ?? null;
            if ($category === null) {
                continue; // holes/text/dimension: drawing helpers, not costed
            }

            $item = $this->resolveItem($component, $category, $overrides[$component->role_code] ?? null);
            $unitCost = $item !== null ? (float) $item->unit_cost : null;
            $unit = $item?->unit_code;

            $quantity = $quantities[$component->role_code]
                ?? ($component->quantity !== null ? (float) $component->quantity : null)
                ?? $this->computeQuantity($component->role_code, $spec);

            $lineTotal = ($quantity !== null && $unitCost !== null)
                ? round($quantity * $unitCost, 2)
                : null;

            $needsInput = $item === null || $quantity === null || $lineTotal === null;
            $hasMissing = $hasMissing || $needsInput;
            if ($lineTotal !== null) {
                $totalCost += $lineTotal;
            }

            if ($persist && $item !== null) {
                $component->update([
                    'price_list_item_id' => $item->id,
                    'quantity' => $quantity,
                    'unit_code' => $unit,
                    'unit_cost' => $unitCost,
                    'line_total' => $lineTotal,
                ]);
            }

            $lines[] = new SignQuotationLine(
                role: $component->role_code,
                label: $component->label,
                priceListItemCode: $item?->code,
                priceListItemName: $item?->name,
                category: $category,
                unit: $unit,
                quantity: $quantity,
                unitCost: $unitCost,
                lineTotal: $lineTotal,
                needsInput: $needsInput,
                note: $this->lineNote($item, $category, $quantity),
            );
        }

        $totalCost = round($totalCost, 2);

        return new SignQuotation(
            signSpecId: $spec->id,
            currency: 'EUR',
            lines: $lines,
            totalCost: $totalCost,
            markupPercent: $markup,
            totalSell: round($totalCost * (1 + $markup / 100), 2),
            hasMissing: $hasMissing,
        );
    }

    private function resolveItem(SignComponent $component, string $category, ?string $overrideCode): ?SignPriceListItem
    {
        if ($overrideCode !== null) {
            return SignPriceListItem::query()->where('code', $overrideCode)->first();
        }

        /** @var list<SignPriceListItem> $candidates */
        $candidates = SignPriceListItem::query()
            ->where('category_code', $category)
            ->where('is_active', true)
            ->orderBy('unit_cost')
            ->get()
            ->all();

        if ($candidates === []) {
            return null;
        }

        // Prefer a candidate whose name matches a token of the component material
        // (e.g. "PLEX", "OTTONE") or its colour temperature (e.g. "3500K").
        foreach ($this->matchTokens($component) as $token) {
            foreach ($candidates as $candidate) {
                if (str_contains(strtoupper($candidate->name), $token)) {
                    return $candidate;
                }
            }
        }

        return $candidates[0];
    }

    /**
     * @return list<string>
     */
    private function matchTokens(SignComponent $component): array
    {
        $tokens = [];
        if ($component->lighting !== null && trim($component->lighting) !== '') {
            $tokens[] = strtoupper(trim($component->lighting));
        }
        foreach (preg_split('/\s+/', (string) $component->material) ?: [] as $word) {
            $word = strtoupper(trim((string) $word));
            if (strlen($word) >= 3) {
                $tokens[] = $word;
            }
        }

        return $tokens;
    }

    /**
     * Applies the editable estimation rule for a role to the drawing geometry.
     * Returns null when there is no active rule or the geometry it needs is
     * missing — the quantity is then left for the operator (never invented).
     */
    private function computeQuantity(string $roleCode, SignSpec $spec): ?float
    {
        $rule = $this->rules[$roleCode] ?? null;
        if ($rule === null || $rule->strategy_code === 'none') {
            return null;
        }

        $base = match ($rule->strategy_code) {
            'area' => $this->geometry($spec, fn (int $w, int $h): float => ($w / 1000) * ($h / 1000)),
            'perimeter' => $this->geometry($spec, fn (int $w, int $h): float => 2 * ($w + $h) / 1000),
            'constant' => 0.0,
            default => null,
        };
        if ($base === null) {
            return null;
        }

        $perPiece = $base * (float) $rule->factor + (float) $rule->base_value;
        if ($rule->min_per_piece !== null) {
            $perPiece = max($perPiece, (float) $rule->min_per_piece);
        }

        $pieces = max(1, $spec->quantity ?? 1);

        return round($perPiece * $pieces, 3);
    }

    /**
     * @param  callable(int, int): float  $formula
     */
    private function geometry(SignSpec $spec, callable $formula): ?float
    {
        if ($spec->width_mm === null || $spec->height_mm === null) {
            return null;
        }

        return $formula($spec->width_mm, $spec->height_mm);
    }

    private function lineNote(?SignPriceListItem $item, string $category, ?float $quantity): ?string
    {
        if ($item === null) {
            return "Nessun articolo a listino per la categoria '{$category}'.";
        }

        if ($quantity === null) {
            return "Quantità da inserire ({$item->unit_code}).";
        }

        return null;
    }
}
