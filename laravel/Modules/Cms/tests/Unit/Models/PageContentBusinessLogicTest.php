<?php

declare(strict_types=1);

namespace Modules\Cms\Tests\Unit\Models;

use Modules\Cms\Models\BaseModel;
use Modules\Cms\Models\PageContent;
use Modules\Cms\Tests\TestCase;
use Modules\Tenant\Models\Traits\SushiToJsons;
use PHPUnit\Framework\Assert;
use Spatie\Translatable\HasTranslations;

uses(TestCase::class);

describe('Page Content Business Logic', function (): void {
    test('page content model can be instantiated', function (): void {
        $pageContent = new PageContent();
        Assert::assertInstanceOf(PageContent::class, $pageContent);
    });

    test('page content extends base model', function (): void {
        $pageContent = new PageContent();
        Assert::assertInstanceOf(BaseModel::class, $pageContent);
    });

    test('page content uses sushi to jsons trait', function (): void {
        $pageContent = new PageContent();
        $traits = class_uses_recursive($pageContent);

        Assert::assertContains(SushiToJsons::class, array_values($traits));
    });

    test('page content uses has translations trait', function (): void {
        $pageContent = new PageContent();
        $traits = class_uses_recursive($pageContent);

        Assert::assertContains(HasTranslations::class, array_values($traits));
    });

    test('page content has correct translatable attributes', function (): void {
        $pageContent = new PageContent();

        Assert::assertContains('name', $pageContent->translatable);

        Assert::assertContains('blocks', $pageContent->translatable);
    });

    test('page content has correct fillable attributes', function (): void {
        $pageContent = new PageContent();
        $fillable = $pageContent->getFillable();

        Assert::assertContains('name', $fillable);

        Assert::assertContains('slug', $fillable);

        Assert::assertContains('blocks', $fillable);
    });

    test('page content has correct schema definition', function (): void {
        $pageContent = new PageContent();

        $reflection = new \ReflectionClass($pageContent);
        $schemaProperty = $reflection->getProperty('schema');

        Assert::assertTrue($schemaProperty->isProtected());

        /** @var array<string, mixed> $schema */
        $schema = $schemaProperty->getValue($pageContent);
        Assert::assertArrayHasKey('id', $schema);
        Assert::assertArrayHasKey('name', $schema);
        Assert::assertArrayHasKey('slug', $schema);
        Assert::assertArrayHasKey('blocks', $schema);
        Assert::assertSame('json', $schema['name']);
        Assert::assertSame('json', $schema['blocks']);
        Assert::assertSame('string', $schema['slug']);
    });

    test('page content has correct casts', function (): void {
        $pageContent = new PageContent();
        /** @var array<string, mixed> $casts */
        $casts = $pageContent->getCasts();
        Assert::assertArrayHasKey('id', $casts);

        Assert::assertArrayHasKey('blocks', $casts);

        Assert::assertArrayHasKey('created_at', $casts);

        Assert::assertArrayHasKey('updated_at', $casts);

        Assert::assertSame('array', $casts['blocks']);
    });

    test('page content get rows method returns array', function (): void {
        $pageContent = new PageContent();
        $rows = $pageContent->getRows();
        Assert::assertNotEmpty($rows);
    });

    test('page content has sluggable configuration', function (): void {
        $pageContent = new PageContent();
        $sluggable = $pageContent->sluggable();
        Assert::assertArrayHasKey('slug', $sluggable);
        /** @var array<string, mixed> $slugConfig */
        $slugConfig = $sluggable['slug'];
        Assert::assertArrayHasKey('source', $slugConfig);

        Assert::assertSame('title', $slugConfig['source']);
    });

    test('page content blocks cast to array', function (): void {
        $pageContent = new PageContent();
        $casts = $pageContent->getCasts();

        Assert::assertSame('array', $casts['blocks']);
    });

    test('page content has datetime casts for timestamps', function (): void {
        $pageContent = new PageContent();
        $casts = $pageContent->getCasts();

        Assert::assertSame('datetime', $casts['created_at']);
        Assert::assertSame('datetime', $casts['updated_at']);
    });
});
