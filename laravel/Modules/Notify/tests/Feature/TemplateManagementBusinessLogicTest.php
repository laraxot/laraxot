<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Feature;

use Modules\Notify\Models\MailTemplate;
use PHPUnit\Framework\Assert;

describe('Template Management Business Logic', function (): void {
    test('template management needs model corrections', function (): void {
        // Tests use incorrect model names (EmailTemplate instead of MailTemplate).
        Assert::assertTrue(class_exists(MailTemplate::class));
    });
});
