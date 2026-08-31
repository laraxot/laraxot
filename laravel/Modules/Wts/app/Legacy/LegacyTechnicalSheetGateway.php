<?php

declare(strict_types=1);

namespace Modules\Wts\Legacy;

interface LegacyTechnicalSheetGateway
{
    public function findBySheetCode(string $sheetCode): ?LegacyTechnicalSheetData;
}
