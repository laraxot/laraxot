<?php

declare(strict_types=1);

namespace Modules\Catalog\Tests {
    use Modules\Catalog\Models\MetelCatalog;
    use PHPUnit\Framework\Assert;

    /**
     * Helper Pest/PHPStan — modulo Catalog.
     *
     * @see Modules/Intervention/tests/PestHelpers.php
     * @see Modules/Timber/tests/PestHelpers.php
     */
    function catalogTest(): TestCase
    {
        Assert::assertNotNull(TestCase::$currentTest);

        return TestCase::$currentTest;
    }

    function catalogSkipTest(string $message = ''): void
    {
        catalogTest()->markTestSkipped($message);
    }

    /**
     * @return array<string, array{0: int, 1: int}>
     */
    function catalogMetelFixedLayout(): array
    {
        /** @var array<string, array{0: int, 1: int}> $layout */
        $layout = config('metel.fixed_layout');

        return $layout;
    }

    /**
     * @return array<string, int>
     */
    function catalogMetelCsvMap(): array
    {
        /** @var array<string, int> $map */
        $map = config('metel.csv_map');

        return $map;
    }

    function catalogMetelFixedPriceDivisor(): int
    {
        $divisor = config('metel.fixed_price_divisor');
        if (is_numeric($divisor)) {
            return (int) $divisor;
        }

        return 1;
    }

    /**
     * @param  array<string, mixed>  $overrides
     */
    function makeMetelCatalog(array $overrides = []): MetelCatalog
    {
        return MetelCatalog::query()->create(array_merge([
            'code' => 'test-cat',
            'brand_code' => 'TST',
            'name' => 'Catalogo Test',
            'source_type' => 'file',
            'is_active' => true,
        ], $overrides));
    }

    /**
     * Builds a fixed-width METEL record line for testing.
     */
    function buildFixedMetelLine(): string
    {
        $line = str_repeat(' ', 140);
        /** @var callable(string, int, string, int): string $put */
        $put = static function (string $line, int $start, string $value, int $length): string {
            $result = substr_replace($line, str_pad(substr($value, 0, $length), $length), $start, $length);

            return is_string($result) ? $result : '';
        };

        $line = $put($line, 0, 'TST', 3);
        $line = $put($line, 3, 'TST0100010', 16);
        $line = $put($line, 57, 'Tubo multistrato 16x2', 50);
        $line = $put($line, 107, 'MT', 3);
        $line = $put($line, 110, '100', 6);
        $line = $put($line, 116, '185', 11);

        return $put($line, 127, '8000000010017', 13);
    }
}
