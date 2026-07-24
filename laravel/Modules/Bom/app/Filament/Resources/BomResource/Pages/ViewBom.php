<?php

declare(strict_types=1);

namespace Modules\Bom\Filament\Resources\BomResource\Pages;

use Filament\Actions;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Illuminate\Support\HtmlString;
use Modules\Bom\Filament\Resources\BomResource;
use Modules\Bom\Models\Bom;
use Modules\Bom\Models\BomItem;

final class ViewBom extends ViewRecord
{
    protected static string $resource = BomResource::class;

    private const MAX_TREE_DEPTH = 8;

    public function getMaxContentWidth(): Width
    {
        return Width::Full;
    }

    public function infolist(Schema $schema): Schema
    {
        /** @var Bom $record */
        $record = $this->getRecord()->loadMissing('product', 'items.componentProduct');

        return $schema
            ->columns(1)
            ->components([
                Section::make('Riepilogo')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextEntry::make('product.name')
                            ->label('Prodotto padre')
                            ->placeholder('—'),
                        TextEntry::make('name')
                            ->label('Nome/versione')
                            ->placeholder('—'),
                        TextEntry::make('items_count')
                            ->label('Componenti diretti')
                            ->state(fn (): int => $record->items->count()),
                    ]),
                Section::make('Albero distinta base')
                    ->columnSpanFull()
                    ->description('Vista leggibile dei componenti collegati al prodotto principale.')
                    ->schema([
                        TextEntry::make('bom_tree')
                            ->label('Struttura')
                            ->state(fn (): HtmlString => new HtmlString($this->renderBomTree($record)))
                            ->html(),
                    ]),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    private function renderBomTree(Bom $bom): string
    {
        $productName = $bom->product?->getAttribute('name');
        $rootLabel = e(is_string($productName) && $productName !== '' ? $productName : ('Prodotto #'.$bom->product_id));
        $directItemsCount = $bom->items->count();
        $maxDepth = (string) self::MAX_TREE_DEPTH;
        $cache = [
            (int) $bom->product_id => $bom,
        ];

        $childrenHtml = $this->renderBomItemsTree(
            $bom,
            1.0,
            [(int) $bom->product_id => true],
            $cache,
            0,
        );

        if ($childrenHtml === '') {
            return <<<HTML
<div class="rounded-2xl border border-dashed border-gray-300 bg-white px-5 py-6 text-sm text-gray-600 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
    <div class="flex flex-col gap-2 lg:flex-row lg:items-center lg:justify-between">
        <div class="text-base font-semibold text-gray-900 dark:text-white">{$rootLabel}</div>
        <div class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-300">Nessun componente</div>
    </div>
    <div class="mt-3 text-sm text-gray-500 dark:text-gray-400">Questa distinta base non contiene ancora elementi collegati.</div>
</div>
HTML;
        }

        return <<<HTML
<div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-900">
    <div class="rounded-2xl border border-primary-200 bg-primary-50/80 px-5 py-4 dark:border-primary-800 dark:bg-primary-950/40">
        <div class="flex flex-col gap-3 xl:flex-row xl:items-center xl:justify-between">
            <div>
                <div class="text-xs font-medium uppercase tracking-[0.18em] text-primary-700 dark:text-primary-300">Prodotto finale</div>
                <div class="mt-1 text-lg font-semibold text-primary-950 dark:text-primary-100">{$rootLabel}</div>
            </div>
            <div class="flex flex-wrap gap-2">
                <span class="inline-flex items-center rounded-full bg-white px-3 py-1 text-xs font-medium text-gray-700 ring-1 ring-primary-100 dark:bg-gray-900 dark:text-gray-200 dark:ring-primary-800">Componenti diretti: {$directItemsCount}</span>
                <span class="inline-flex items-center rounded-full bg-white px-3 py-1 text-xs font-medium text-gray-700 ring-1 ring-primary-100 dark:bg-gray-900 dark:text-gray-200 dark:ring-primary-800">Profondita max: {$maxDepth}</span>
            </div>
        </div>
    </div>
    <div class="mt-5 space-y-4">
        {$childrenHtml}
    </div>
</div>
HTML;
    }

    /**
     * @param  array<int, bool>  $pathProductIds
     * @param  array<int, Bom|null>  $cache
     */
    private function renderBomItemsTree(Bom $bom, float $parentMultiplier, array $pathProductIds, array &$cache, int $depth): string
    {
        $items = $bom->items
            ->sortBy(function (BomItem $item): string {
                $name = $item->componentProduct?->getAttribute('name');

                return mb_strtolower(is_string($name) ? $name : '');
            })
            ->values();

        if ($items->isEmpty()) {
            return '';
        }

        $html = '<ul class="space-y-4">';

        foreach ($items as $item) {
            $componentProductId = $item->component_product_id;
            $componentProductName = $item->componentProduct?->getAttribute('name');
            $componentLabel = e(is_string($componentProductName) && $componentProductName !== '' ? $componentProductName : ('Componente #'.$componentProductId));
            $unitQuantity = (float) $item->quantity;
            $totalQuantity = $parentMultiplier * $unitQuantity;
            $nestedHtml = '';

            if (isset($pathProductIds[$componentProductId])) {
                $nestedHtml = '<div class="mt-3 rounded-xl border border-amber-200 bg-amber-50 px-3 py-3 text-xs text-amber-800 dark:border-amber-900 dark:bg-amber-950/30 dark:text-amber-200">Rilevato riferimento circolare: questa diramazione viene interrotta per evitare ricorsioni infinite.</div>';
            } elseif ($depth >= self::MAX_TREE_DEPTH) {
                $nestedHtml = '<div class="mt-3 rounded-xl border border-amber-200 bg-amber-50 px-3 py-3 text-xs text-amber-800 dark:border-amber-900 dark:bg-amber-950/30 dark:text-amber-200">Profondita massima raggiunta: apri il componente dedicato per continuare l\'analisi.</div>';
            } else {
                $childBom = $this->getActiveBomForProduct($componentProductId, $cache);

                if ($childBom !== null) {
                    $nestedPath = $pathProductIds;
                    $nestedPath[$componentProductId] = true;

                    $childHtml = $this->renderBomItemsTree(
                        $childBom,
                        $totalQuantity,
                        $nestedPath,
                        $cache,
                        $depth + 1,
                    );

                    if ($childHtml !== '') {
                        $nestedHtml = '<div class="mt-4 rounded-2xl border border-gray-200/80 bg-gray-50/80 p-4 dark:border-gray-700 dark:bg-gray-800/40">'.$childHtml.'</div>';
                    }
                }
            }

            $html .= sprintf(
                '<li class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                    <div class="flex items-start gap-4">
                        <div class="mt-1 h-3 w-3 shrink-0 rounded-full bg-primary-500 ring-4 ring-primary-100 dark:ring-primary-950"></div>
                        <div class="min-w-0 flex-1 space-y-3">
                            <div class="flex flex-col gap-3 xl:flex-row xl:items-start xl:justify-between">
                                <div class="space-y-1">
                                    <div class="text-xs font-medium uppercase tracking-[0.18em] text-gray-400 dark:text-gray-500">Livello %s</div>
                                    <div class="text-sm font-semibold text-gray-900 dark:text-white">%s</div>
                                </div>
                                <div class="flex flex-wrap gap-2 text-xs">
                                    <span class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 font-medium text-gray-700 dark:bg-gray-800 dark:text-gray-200">Qta per padre: %s</span>
                                    <span class="inline-flex items-center rounded-full bg-primary-50 px-3 py-1 font-medium text-primary-800 ring-1 ring-primary-200 dark:bg-primary-950/40 dark:text-primary-200 dark:ring-primary-800">Qta totale: %s</span>
                                </div>
                            </div>
                            %s
                        </div>
                    </div>
                </li>',
                e((string) ($depth + 1)),
                $componentLabel,
                e($this->formatQuantity($unitQuantity)),
                e($this->formatQuantity($totalQuantity)),
                $nestedHtml,
            );
        }

        $html .= '</ul>';

        return $html;
    }

    /**
     * @param  array<int, Bom|null>  $cache
     */
    private function getActiveBomForProduct(int $productId, array &$cache): ?Bom
    {
        if (array_key_exists($productId, $cache)) {
            return $cache[$productId];
        }

        $cache[$productId] = Bom::query()
            ->with(['product', 'items.componentProduct'])
            ->where('product_id', $productId)
            ->where('is_active', true)
            ->first();

        return $cache[$productId];
    }

    private function formatQuantity(float $quantity): string
    {
        $formatted = number_format($quantity, 3, '.', '');
        $trimmed = rtrim(rtrim($formatted, '0'), '.');

        return $trimmed === '' ? '0' : $trimmed;
    }
}
