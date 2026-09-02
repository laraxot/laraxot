<?php

declare(strict_types=1);

namespace Modules\Rating\Tests\Unit;

use Illuminate\Database\Eloquent\Builder;
use Mockery;
use Modules\Rating\Enums\RuleEnum;
use Modules\Rating\Models\BaseRating;
use Modules\Rating\Models\Rating;
use Modules\Rating\Tests\TestCase;
use PHPUnit\Framework\Assert;
use Spatie\Sluggable\SlugOptions;

uses(TestCase::class);

afterEach(function (): void {
    \Mockery::close();
});

describe('BaseRating (via Rating)', function (): void {
    test('getSlugOptions genera slug dal titolo', function (): void {
        $options = (new Rating())->getSlugOptions();

        Assert::assertInstanceOf(SlugOptions::class, $options);
    });

    test('registerMediaConversions non solleva eccezioni', function (): void {
        $rating = new Rating();

        $rating->registerMediaConversions(null);

        Assert::assertInstanceOf(Rating::class, $rating);
    });

    test('linkedTo restituisce relazione morphTo', function (): void {
        $relation = (new Rating())->linkedTo();

        Assert::assertSame('model_type', $relation->getMorphType());
        Assert::assertSame('model_id', $relation->getForeignKeyName());
    });

    test('casts include extra_attributes rule e boolean', function (): void {
        $rating = new Rating([
            'rule' => RuleEnum::ZeroFive,
            'is_disabled' => '1',
            'is_readonly' => '0',
        ]);

        Assert::assertInstanceOf(RuleEnum::class, $rating->rule);
        Assert::assertSame(RuleEnum::ZeroFive, $rating->rule);
        Assert::assertTrue($rating->is_disabled);
        Assert::assertFalse($rating->is_readonly);
    });

    test('scopeWithExtraAttributes filtra per chiave singola con valore', function (): void {
        /** @var Builder<BaseRating>&Mockery\MockInterface $builder */
        $builder = \Mockery::mock(Builder::class);
        $builder->shouldReceive('where')
            ->once()
            ->with('extra_attributes->anno', 2024)
            ->andReturnSelf();

        $result = (new Rating())->scopeWithExtraAttributes($builder, 'anno', 2024);

        Assert::assertSame($builder, $result);
    });

    test('scopeWithExtraAttributes filtra per array di attributi', function (): void {
        /** @var Builder<BaseRating>&Mockery\MockInterface $builder */
        $builder = \Mockery::mock(Builder::class);
        $builder->shouldReceive('where')
            ->once()
            ->with('extra_attributes->anno', 2024)
            ->andReturnSelf();
        $builder->shouldReceive('where')
            ->once()
            ->with('extra_attributes->tipo', 'foo')
            ->andReturnSelf();

        $result = (new Rating())->scopeWithExtraAttributes($builder, [
            'anno' => 2024,
            'tipo' => 'foo',
        ]);

        Assert::assertSame($builder, $result);
    });

    test('scopeWithExtraAttributes senza value su stringa lascia il builder', function (): void {
        /** @var Builder<BaseRating>&Mockery\MockInterface $builder */
        $builder = \Mockery::mock(Builder::class);
        $builder->shouldNotReceive('where');

        $result = (new Rating())->scopeWithExtraAttributes($builder, 'anno');

        Assert::assertSame($builder, $result);
    });
});
