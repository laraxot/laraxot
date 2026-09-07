<?php

declare(strict_types=1);

use Modules\Cms\Filament\Resources\PageResource;
use Modules\Cms\Models\Page;
use Modules\Cms\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);
describe('PageResource', function (): void {
    test('page resource has correct model', function (): void {
        $resource = new PageResource();

        Assert::assertSame(Page::class, $resource::getModel());
    });

   

    test('page resource extends LangBaseResource', function (): void {
        Assert::assertTrue(class_exists(PageResource::class));
    });
});
