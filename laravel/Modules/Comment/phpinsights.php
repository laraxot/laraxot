<?php

declare(strict_types=1);

use NunoMaduro\PhpInsights\Domain\Insights\ForbiddenDefineFunctions;
use NunoMaduro\PhpInsights\Domain\Insights\ForbiddenFinalClasses;
use NunoMaduro\PhpInsights\Domain\Insights\ForbiddenNormalClasses;
use NunoMaduro\PhpInsights\Domain\Insights\ForbiddenSecurityIssues;
use NunoMaduro\PhpInsights\Domain\Insights\ForbiddenTraits;
use NunoMaduro\PhpInsights\Domain\Insights\MethodCyclomaticComplexityIsHigh;
use NunoMaduro\PhpInsights\Domain\Metrics\Architecture\Classes;
use NunoMaduro\PhpInsights\Domain\Sniffs\ForbiddenSetterSniff;

return [
    'preset' => 'laravel',
    'ide' => null,
    'exclude' => [
        'packages',
        'vendor',
        'node_modules',
        'storage',
        'bootstrap/cache',
        'tests',
        'database/seeders',
        'database/factories',
        'config',
        'resources/lang',
    ],
    'add' => [
        Classes::class => [
            ForbiddenFinalClasses::class,
        ],
    ],
    'remove' => [
        ForbiddenDefineFunctions::class,
        ForbiddenNormalClasses::class,
        ForbiddenSecurityIssues::class,
        ForbiddenSetterSniff::class,
        ForbiddenTraits::class,
        MethodCyclomaticComplexityIsHigh::class,
    ],
    'config' => [],
    'requirements' => [
        'min-quality' => 100,
        'min-complexity' => 100,
        'min-architecture' => 100,
        'min-style' => 100,
    ],
    'threads' => null,
    'timeout' => 120,
];
