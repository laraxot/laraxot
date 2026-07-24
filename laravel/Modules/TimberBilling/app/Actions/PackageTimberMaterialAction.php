<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Actions;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Modules\TimberBilling\Models\TimberDerivedMaterial;
use Modules\TimberBilling\Models\TimberPackagedUnit;
use Modules\TimberBilling\Models\TimberProduct;
use Modules\TimberBilling\Support\TimberTraceCode;

/**
 * Confeziona un semilavorato in uno o piu' articoli di catalogo (es. meta' sacchi,
 * meta' bancali). Per ogni articolo: arricchisce il codice tracciabile con
 * ·CNF-mmgg-ART{codice}, crea le confezioni e carica il prodotto finito a magazzino.
 */
final class PackageTimberMaterialAction
{
    public function __construct(
        private readonly GenerateTimberPackagedUnitsAction $generatePackagedUnits,
        private readonly RegisterTimberStockMovementAction $registerStockMovement,
    ) {}

    /**
     * @param  array<int, array{product_id:int, package_type_code:string, packages_count:int, quantity_per_package:float}>  $lines
     * @return Collection<int, TimberPackagedUnit>
     */
    public function execute(TimberDerivedMaterial $material, array $lines, ?int $userId = null): Collection
    {
        if ($lines === []) {
            throw new InvalidArgumentException('Indicare almeno un articolo da confezionare.');
        }

        if ($material->is_waste) {
            throw new InvalidArgumentException('Il materiale di scarto non puo essere confezionato.');
        }

        return DB::transaction(function () use ($material, $lines, $userId): Collection {
            /** @var Collection<int, TimberPackagedUnit> $allUnits */
            $allUnits = collect();

            foreach ($lines as $line) {
                $product = TimberProduct::query()->findOrFail($line['product_id']);
                $packagesCount = (int) $line['packages_count'];
                $quantityPerPackage = (float) $line['quantity_per_package'];
                $articleCode = (string) ($product->code ?? (string) $product->id);

                $baseCode = trim((string) ($material->traceable_code ?? '')) !== ''
                    ? (string) $material->traceable_code
                    : (string) $material->output_code;
                $lineTraceCode = TimberTraceCode::append(
                    $baseCode,
                    TimberTraceCode::segment('confezionamento', now(), 'ART'.$articleCode),
                );

                $units = $this->generatePackagedUnits->execute(
                    material: $material,
                    packageTypeCode: (string) $line['package_type_code'],
                    unitsCount: $packagesCount,
                    quantityPerUnit: $quantityPerPackage,
                    userId: $userId,
                    articleCode: $articleCode,
                    traceableCode: $lineTraceCode,
                );

                // Prodotto finito a magazzino.
                $this->registerStockMovement->execute(
                    product: $product,
                    directionCode: 'load',
                    quantity: $packagesCount * $quantityPerPackage,
                    unitOfMeasure: (string) $material->unit_of_measure,
                    userId: $userId,
                    documentTypeCode: 'confezionamento',
                    documentReference: $lineTraceCode,
                    lotCode: $lineTraceCode,
                );

                $allUnits = $allUnits->concat($units);
                $material->refresh();
            }

            return $allUnits;
        });
    }
}
