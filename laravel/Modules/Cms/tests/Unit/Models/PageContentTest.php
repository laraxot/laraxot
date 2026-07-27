<?php

declare(strict_types=1);

use Modules\Cms\Models\BaseModel;
use Modules\Cms\Models\PageContent;
use Modules\Cms\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);
test('PageContent model can be instantiated', function () {
    $pageContent = new PageContent();

    Assert::assertInstanceOf(PageContent::class, $pageContent);
});

test('PageContent model has expected fillable fields', function () {
    $pageContent = new PageContent();

    $fillable = $pageContent->getFillable();

    Assert::assertContains('name', $fillable);

    Assert::assertContains('slug', $fillable);

    Assert::assertContains('blocks', $fillable);
});

test('PageContent model extends BaseModel', function () {
    $pageContent = new PageContent();

    Assert::assertInstanceOf(BaseModel::class, $pageContent);
});

test('PageContent model has translatable fields', function () {
    $pageContent = new PageContent();

    Assert::assertContains('name', $pageContent->translatable);
    Assert::assertContains('blocks', $pageContent->translatable);
});
