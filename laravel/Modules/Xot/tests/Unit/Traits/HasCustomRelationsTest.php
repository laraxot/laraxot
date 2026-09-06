<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Model;
use Modules\Xot\Relations\CustomRelation;
use Modules\Xot\Tests\TestCase;
use Modules\Xot\Traits\HasCustomRelations;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

it('creates custom relation', function (): void {
    $relatedModel = new class extends Model
    {
        protected $table = 'related';
    };

    $parentModel = new class extends Model
    {
        use HasCustomRelations;

        protected $table = 'parent';
    };

    $baseConstraints = fn (mixed $relation) => null;
    $eagerConstraints = fn (mixed $relation, mixed $models) => null;
    $eagerMatcher = fn (mixed $models, mixed $results, mixed $relation) => [];

    $relation = $parentModel->customRelation(
        get_class($relatedModel),
        $baseConstraints,
        $eagerConstraints,
        $eagerMatcher
    );

    Assert::assertInstanceOf(CustomRelation::class, $relation);
});
