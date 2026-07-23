<?php

declare(strict_types=1);

return [
    'preset' => 'laravel',
    'ide' => null,
    'exclude' => [
        'tests/Support/helpers.php',
    ],
    'add' => [
        'NunoMaduro\PhpInsights\Domain\Metrics\Architecture\Classes' => [
            'NunoMaduro\PhpInsights\Domain\Insights\ForbiddenFinalClasses',
        ],
    ],
    'remove' => [
        'SlevomatCodingStandard\Sniffs\Namespaces\AlphabeticallySortedUsesSniff',
        'SlevomatCodingStandard\Sniffs\TypeHints\DeclareStrictTypesSniff',
        'SlevomatCodingStandard\Sniffs\TypeHints\DisallowMixedTypeHintSniff',
        'SlevomatCodingStandard\Sniffs\ControlStructures\DisallowYodaComparisonSniff',
        'NunoMaduro\PhpInsights\Domain\Insights\ForbiddenDefineFunctions',
        'NunoMaduro\PhpInsights\Domain\Insights\ForbiddenNormalClasses',
        'NunoMaduro\PhpInsights\Domain\Sniffs\ForbiddenPublicPropertySniff',
        'NunoMaduro\PhpInsights\Domain\Sniffs\ForbiddenSetterSniff',
        'NunoMaduro\PhpInsights\Domain\Insights\ForbiddenTraits',
        'PHP_CodeSniffer\Standards\PEAR\Sniffs\Functions\FunctionDeclarationSniff',
        'PHP_CodeSniffer\Standards\Generic\Sniffs\Files\LineLengthSniff',
        'SlevomatCodingStandard\Sniffs\TypeHints\ParameterTypeHintSniff',
        'SlevomatCodingStandard\Sniffs\TypeHints\PropertyTypeHintSniff',
        'SlevomatCodingStandard\Sniffs\TypeHints\ReturnTypeHintSniff',
        'SlevomatCodingStandard\Sniffs\Functions\StaticClosureSniff',
        'SlevomatCodingStandard\Sniffs\Commenting\UselessFunctionDocCommentSniff',
        'PHP_CodeSniffer\Standards\Generic\Sniffs\CodeAnalysis\EmptyStatementSniff',
    ],
    'config' => [
        'NunoMaduro\PhpInsights\Domain\Insights\ForbiddenPrivateMethods' => [
            'title' => 'The usage of private methods is not idiomatic in Laravel.',
        ],
    ],
    'requirements' => [
        'min-quality' => 90,
        'min-complexity' => 84,
        'min-architecture' => 80,
        'min-style' => 95,
    ],
    'threads' => null,
    'timeout' => 120,
];
