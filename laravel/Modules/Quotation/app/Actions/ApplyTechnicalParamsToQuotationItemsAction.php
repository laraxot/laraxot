<?php

declare(strict_types=1);

namespace Modules\Quotation\Actions;

use Modules\Catalog\Models\Product;
use Modules\Quotation\Domain\DTO\DictatedQuotationItem;

/**
 * Recomputes the rule-governed line items of a quotation from the technical
 * parameters (m², flue length, thermostat present, …) using the CURRENT catalog
 * prices. Lines not governed by any rule are preserved untouched — "change only
 * what is necessary". Rules and their product SKUs come from config.
 */
final class ApplyTechnicalParamsToQuotationItemsAction
{
    /**
     * @param  list<DictatedQuotationItem>  $items
     * @param  array<string, mixed>  $params
     * @return list<DictatedQuotationItem>
     */
    public function execute(array $items, array $params): array
    {
        /** @var list<array<string, mixed>> $rules */
        $rules = array_values(array_filter((array) config('quotation_technical.rules', []), 'is_array'));

        /** @var array<int, true> $ruleProductIds */
        $ruleProductIds = [];
        /** @var list<DictatedQuotationItem> $ruleLines */
        $ruleLines = [];

        foreach ($rules as $rule) {
            $sku = is_string($rule['product_sku'] ?? null) ? $rule['product_sku'] : '';
            if ($sku === '') {
                continue;
            }

            $product = Product::query()->where('sku', $sku)->where('is_active', true)->first();
            if ($product === null) {
                continue;
            }

            $productId = (int) $product->id;
            $ruleProductIds[$productId] = true;

            $condition = is_array($rule['active_when'] ?? null) ? $rule['active_when'] : [];
            if (! $this->isActive($condition, $params)) {
                continue;
            }

            $quantity = $this->quantity($rule, $params);
            if ($quantity <= 0) {
                continue;
            }

            $ruleLines[] = new DictatedQuotationItem(
                description: is_string($product->name) && $product->name !== '' ? $product->name : $sku,
                quantity: $quantity,
                unitPrice: $product->unit_price !== null ? (float) $product->unit_price : null,
                matchedProductId: $productId,
            );
        }

        // Preserve everything that is not governed by a rule; replace rule lines.
        $preserved = array_values(array_filter(
            $items,
            static fn (DictatedQuotationItem $item): bool => $item->matchedProductId === null
                || ! isset($ruleProductIds[$item->matchedProductId]),
        ));

        return array_values([...$preserved, ...$ruleLines]);
    }

    /**
     * @param  array<string, mixed>  $condition
     * @param  array<string, mixed>  $params
     */
    private function isActive(array $condition, array $params): bool
    {
        $param = is_string($condition['param'] ?? null) ? $condition['param'] : '';
        if ($param === '') {
            return false;
        }

        $value = $params[$param] ?? null;
        $op = is_string($condition['op'] ?? null) ? $condition['op'] : 'truthy';

        return match ($op) {
            'gt' => is_numeric($value) && (float) $value > $this->floatOr($condition['value'] ?? 0),
            'truthy' => $this->truthy($value),
            'falsey' => ! $this->truthy($value),
            default => false,
        };
    }

    /**
     * @param  array<string, mixed>  $rule
     * @param  array<string, mixed>  $params
     */
    private function quantity(array $rule, array $params): float
    {
        if (isset($rule['quantity_from']) && is_string($rule['quantity_from'])) {
            $value = $params[$rule['quantity_from']] ?? null;

            return is_numeric($value) ? (float) $value : 0.0;
        }

        return $this->floatOr($rule['quantity'] ?? 1, 1.0);
    }

    private function truthy(mixed $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        if (is_numeric($value)) {
            return (float) $value !== 0.0;
        }

        if (is_string($value)) {
            $normalized = mb_strtolower(trim($value));

            return $normalized !== '' && ! in_array($normalized, ['0', 'no', 'false', 'assente'], true);
        }

        return false;
    }

    private function floatOr(mixed $value, float $default = 0.0): float
    {
        return is_numeric($value) ? (float) $value : $default;
    }
}
