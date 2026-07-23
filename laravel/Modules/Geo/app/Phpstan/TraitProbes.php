<?php

declare(strict_types=1);

namespace Modules\Geo\Phpstan;

use Modules\Geo\Models\BaseModel;
use Modules\Geo\Models\Traits\GeoTrait;
use Modules\Geo\Models\Traits\HasAddress;
use Modules\Geo\Models\Traits\HasPlaceTrait;
use Modules\Geo\Traits\HasAddresses;

/**
 * @internal PHPStan probes — tests/ excluded from scan
 *
 * @property int|string|null $id
 * @property mixed|null      $address
 */
abstract class GeoPhpstanProbeModel extends BaseModel
{
    protected $table = 'geo_phpstan_trait_probes';
}

final class GeoTraitPhpstanProbe extends GeoPhpstanProbeModel
{
    use GeoTrait;
}

final class HasAddressPhpstanProbe extends GeoPhpstanProbeModel
{
    use HasAddress;
}

final class HasPlaceTraitPhpstanProbe extends GeoPhpstanProbeModel
{
    use HasPlaceTrait;
}

final class HasAddressesPhpstanProbe extends GeoPhpstanProbeModel
{
    use HasAddresses;
}
