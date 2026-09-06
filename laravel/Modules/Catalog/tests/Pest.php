<?php

declare(strict_types=1);

require_once __DIR__.'/PestHelpers.php';

// Wrapper functions in global namespace for PHPStan and Pest
namespace {
    use Modules\Catalog\Models\MetelCatalog;
    use Modules\Catalog\Tests\TestCase;

    if (! function_exists('catalogTest')) {
        function catalogTest(): TestCase {
            return \Modules\Catalog\Tests\catalogTest();
        }
    }

    if (! function_exists('catalogSkipTest')) {
        function catalogSkipTest(string $message = ''): void {
            \Modules\Catalog\Tests\catalogSkipTest($message);
        }
    }

    if (! function_exists('catalogMetelFixedLayout')) {
        /**
         * @return array<string, array{0: int, 1: int}>
         */
        function catalogMetelFixedLayout(): array {
            return \Modules\Catalog\Tests\catalogMetelFixedLayout();
        }
    }

    if (! function_exists('catalogMetelCsvMap')) {
        /**
         * @return array<string, int>
         */
        function catalogMetelCsvMap(): array {
            return \Modules\Catalog\Tests\catalogMetelCsvMap();
        }
    }

    if (! function_exists('catalogMetelFixedPriceDivisor')) {
        function catalogMetelFixedPriceDivisor(): int {
            return \Modules\Catalog\Tests\catalogMetelFixedPriceDivisor();
        }
    }

    if (! function_exists('makeMetelCatalog')) {
        /**
         * @param array<string, mixed> $overrides
         */
        function makeMetelCatalog(array $overrides = []): MetelCatalog {
            return \Modules\Catalog\Tests\makeMetelCatalog($overrides);
        }
    }

    if (! function_exists('buildFixedMetelLine')) {
        function buildFixedMetelLine(): string {
            return \Modules\Catalog\Tests\buildFixedMetelLine();
        }
    }
}
