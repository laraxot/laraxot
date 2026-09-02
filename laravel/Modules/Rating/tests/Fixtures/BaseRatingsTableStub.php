<?php

declare(strict_types=1);

namespace Modules\Rating\Tests\Fixtures;

use Modules\Rating\Filament\Resources\RatingResource\Tables\BaseRatingsTable;

/**
 * Stub concreto: evita classi anonime che in alcuni contesti PHPUnit
 * innescano side-effect del costruttore XotBaseResourceTable.
 */
final class BaseRatingsTableStub extends BaseRatingsTable {}
