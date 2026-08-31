<?php

declare(strict_types=1);

namespace Modules\Xot\PHPStan;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PHPStan\Analyser\Error;
use PHPStan\Analyser\IgnoreErrorExtension;
use PHPStan\Analyser\Scope;

/**
 * Pest 4 non ha pest-plugin-phpstan (richiede Pest ^5).
 * Finché lo stack resta su Pest 4 / PHPUnit 12, sopprimiamo solo gli accessi
 * a tipi interni Pest\* usati legittimamente nei test namespaced.
 *
 * Allineato a Pest\PHPStan\Type\Pest\PestInternalClassAccessIgnoreExtension (Pest 5).
 */
final class PestInternalClassAccessIgnoreExtension implements IgnoreErrorExtension
{
    /** @var list<string> */
    private const array SUPPRESSED_IDENTIFIERS = [
        'property.internalClass',
        'method.internalClass',
        'method.internalTrait',
        'method.internalInterface',
    ];

    public function shouldIgnore(Error $error, Node $node, Scope $scope): bool
    {
        if (! in_array($error->getIdentifier(), self::SUPPRESSED_IDENTIFIERS, true)) {
            return false;
        }

        if (! $node instanceof MethodCall && ! $node instanceof PropertyFetch) {
            return false;
        }

        foreach ($scope->getType($node->var)->getReferencedClasses() as $class) {
            if (str_starts_with($class, 'Pest\\')) {
                return true;
            }
        }

        return false;
    }
}
