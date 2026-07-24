<?php

declare(strict_types=1);

namespace Modules\WhatsApp\AI;

use Modules\WhatsApp\Models\WhatsAppMessage;

interface WhatsAppMessageClassifierInterface
{
    /**
     * @param  array<string, mixed>  $context
     * @return array<string, mixed>
     */
    public function classify(WhatsAppMessage $message, array $context): array;
}
