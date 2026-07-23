<?php

declare(strict_types=1);

namespace Modules\Auth\Support;

final class MasterPasswordContext
{
    private bool $used = false;

    public function markUsed(): void
    {
        $this->used = true;
    }

    public function wasUsed(): bool
    {
        return $this->used;
    }
}
