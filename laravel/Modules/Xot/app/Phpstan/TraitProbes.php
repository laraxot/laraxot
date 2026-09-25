<?php

declare(strict_types=1);

namespace Modules\Xot\Phpstan;

use Illuminate\Support\Carbon;
use Modules\Xot\Models\Traits\HasCommonScopes;
use Modules\Xot\Models\XotBaseModel;
use Modules\Xot\Traits\HasCustomRelations;
use Modules\Xot\Traits\HasSchemalessAttributes;
use Spatie\SchemalessAttributes\SchemalessAttributes;

/**
 * PHPStan probes — tests/ excluded from scan.
 */
abstract class XotPhpstanProbeModel extends XotBaseModel
{
    protected $table = 'xot_phpstan_trait_probes';
}

/**
 * @property bool|null   $is_active
 * @property Carbon|null $published_at
 */
final class HasCommonScopesPhpstanProbe extends XotPhpstanProbeModel
{
    use HasCommonScopes;
}

final class HasCustomRelationsPhpstanProbe extends XotPhpstanProbeModel
{
    use HasCustomRelations;
}

/**
 * @property SchemalessAttributes|null $extra_attributes
 */
final class HasSchemalessAttributesPhpstanProbe extends XotPhpstanProbeModel
{
    use HasSchemalessAttributes;
}
