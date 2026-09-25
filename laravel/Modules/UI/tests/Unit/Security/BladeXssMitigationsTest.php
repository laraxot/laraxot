<?php

declare(strict_types=1);

use PHPUnit\Framework\Assert;

use function Safe\file_get_contents;

it('button blade avoids unescaped dynamic tag emission', function (): void {
    $path = dirname(__DIR__, 3).'/resources/views/components/ui/button.blade.php';
    $contents = (string) file_get_contents($path);

    Assert::assertStringNotContainsString('{!!', $contents);
    Assert::assertStringContainsString('preg_match', $contents);
    Assert::assertStringContainsString('javascript:', $contents);
});

it('educational material detail sanitizes html content with allowlist', function (): void {
    $path = dirname(__DIR__, 3).'/resources/views/components/blocks/educational_material_detail.blade.php';
    $contents = (string) file_get_contents($path);

    Assert::assertStringContainsString('strip_tags', $contents);
    Assert::assertStringNotContainsString('{!! $material->content !!}', $contents);
});

it('language controller rejects off-site previous url', function (): void {
    $source = (string) file_get_contents(
        dirname(__DIR__, 3).'/app/Http/Controllers/LanguageController.php'
    );

    Assert::assertStringContainsString('str_starts_with($previous, $fallback)', $source);
    Assert::assertStringNotContainsString('redirect()->back()', $source);
});
