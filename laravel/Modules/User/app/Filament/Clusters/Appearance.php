<?php

declare(strict_types=1);

namespace Modules\User\Filament\Clusters;

<<<<<<< HEAD
<<<<<<< HEAD
use Modules\Xot\Filament\Clusters\XotBaseCluster;

/**
 * Cluster Appearance per raggruppare pagine di personalizzazione aspetto.
 *
 * ⚠️ IMPORTANTE: Estende XotBaseCluster, MAI Filament\Clusters\Cluster direttamente!
 *
 * @see XotBaseCluster
 * @see \Modules\User\docs\errori\class-page-not-found.md
 */
class Appearance extends XotBaseCluster
{
=======
=======
>>>>>>> 87273113 (.)
use Filament\Clusters\Cluster;

class Appearance extends Cluster
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-squares-2x2';
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Modules\Xot\Filament\Clusters\XotBaseCluster;

/**
 * Cluster Appearance per raggruppare pagine di personalizzazione aspetto.
 *
 * ⚠️ IMPORTANTE: Estende XotBaseCluster, MAI Filament\Clusters\Cluster direttamente!
 *
 * @see XotBaseCluster
 * @see \Modules\User\docs\errori\class-page-not-found.md
 */
class Appearance extends XotBaseCluster
{
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
}
