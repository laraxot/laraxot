<?php

declare(strict_types=1);

namespace Modules\Email\AI;

use Modules\Email\Models\EmailMessage;

interface EmailMessageClassifierInterface
{
    /**
     * @param  array<string, mixed>  $context
     * @return array<string, mixed>
     */
    public function classify(EmailMessage $message, array $context): array;
}
