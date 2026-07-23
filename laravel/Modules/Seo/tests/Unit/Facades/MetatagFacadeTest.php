<?php

declare(strict_types=1);

namespace Modules\Seo\Tests\Unit\Facades;

use Modules\Seo\Facades\Metatag;
use Modules\Seo\Adapters\MetatagManager;
use PHPUnit\Framework\Assert;
uses(\Modules\Seo\Tests\TestCase::class);

it('resolves metatag service through facade accessor', function (): void {
    $service = app(MetatagManager::class);

    Metatag::setTitle('Facade Title');
    Metatag::setDescription('Facade Description');

    Assert::assertSame('Facade Title', $service->get()->getTitle());
    Assert::assertSame('Facade Description', $service->get()->getDescription());
});
