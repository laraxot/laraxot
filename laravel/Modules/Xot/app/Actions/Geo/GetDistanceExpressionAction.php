<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Geo;

use Illuminate\Contracts\Database\Query\Expression;
use Modules\Xot\Database\Query\GeoDistanceExpression;
use Spatie\QueueableAction\QueueableAction;

/**
 * Action per generare l'espressione SQL per il calcolo della distanza.
 *
 * Questa action centralizza la logica di generazione dell'espressione SQL
 * per il calcolo della distanza tra due punti geografici.
 */
class GetDistanceExpressionAction
{
    use QueueableAction;

    /**
     * Genera l'espressione SQL per calcolare la distanza tra due punti.
     *
<<<<<<< HEAD
     * @param  float  $latitude  Latitudine del punto di riferimento
     * @param  float  $longitude  Longitudine del punto di riferimento
     * @param  string|null  $alias  Alias per l'espressione (opzionale)
=======
<<<<<<< .merge_file_bn7Phq
     * @param  float  $latitude  Latitudine del punto di riferimento
     * @param  float  $longitude  Longitudine del punto di riferimento
     * @param  string|null  $alias  Alias per l'espressione (opzionale)
=======
     * <<<<<<< HEAD
     *
     * @param float       $latitude  Latitudine del punto di riferimento
     * @param float       $longitude Longitudine del punto di riferimento
     * @param string|null $alias     Alias per l'espressione (opzionale)
     *                               =======
     *                               <<<<<<< HEAD
     * @param float       $latitude  Latitudine del punto di riferimento
     * @param float       $longitude Longitudine del punto di riferimento
     * @param string|null $alias     Alias per l'espressione (opzionale)
     *                               =======
     *                               <<<<<<< HEAD
     *                               <<<<<<< HEAD
     * @param float       $latitude  Latitudine del punto di riferimento
     * @param float       $longitude Longitudine del punto di riferimento
     * @param string|null $alias     Alias per l'espressione (opzionale)
     *                               =======
     * @param float       $latitude  Latitudine del punto di riferimento
     * @param float       $longitude Longitudine del punto di riferimento
     * @param string|null $alias     Alias per l'espressione (opzionale)
     *
     * >>>>>>> laraxot/dev
     *
     * =======
     * @param float       $latitude  Latitudine del punto di riferimento
     * @param float       $longitude Longitudine del punto di riferimento
     * @param string|null $alias     Alias per l'espressione (opzionale)
     *                               >>>>>>> laraxot/dev
     *                               >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     *
>>>>>>> .merge_file_gonqvM
>>>>>>> 77b1e2748 (first)
     * @return Expression Espressione SQL per il calcolo della distanza
     */
    public function execute(
        float $latitude,
        float $longitude,
        ?string $alias = null,
    ): Expression {
        return new GeoDistanceExpression($latitude, $longitude, $alias);
    }
}
