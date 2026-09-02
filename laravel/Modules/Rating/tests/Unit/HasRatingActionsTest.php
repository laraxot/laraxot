<?php

declare(strict_types=1);

namespace Modules\Rating\Tests\Unit;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Mockery;
use Modules\Rating\Actions\HasRating\GetCountByModelRatingIdAction;
use Modules\Rating\Actions\HasRating\GetRatingOptsByModelAction;
use Modules\Rating\Actions\HasRating\GetSumByModelRatingIdAction;
use Modules\Rating\Models\Contracts\HasRatingContract;
use Modules\Rating\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

/**
 * @return Mockery\MockInterface&HasRatingContract
 */
function ratingMockHost(Mockery\MockInterface $relation): HasRatingContract
{
    /** @var Mockery\MockInterface&HasRatingContract $host */
    $host = \Mockery::mock(HasRatingContract::class);
    $host->shouldReceive('ratings')->andReturn($relation);

    return $host;
}

describe('HasRating Actions', function (): void {
    test('GetCountByModelRatingIdAction conta i pivot con user', function (): void {
        $relation = \Mockery::mock(MorphToMany::class);
        $relation->shouldReceive('wherePivot')->with('user_id', '!=', null)->andReturnSelf();
        $relation->shouldReceive('wherePivot')->with('rating_id', '7')->andReturnSelf();
        $relation->shouldReceive('count')->with('rating_morph.value')->andReturn(4);

        $action = new GetCountByModelRatingIdAction();
        $count = $action->execute(ratingMockHost($relation), '7');

        Assert::assertSame(4.0, $count);
    });

    test('GetSumByModelRatingIdAction somma i punti pivot', function (): void {
        $relation = \Mockery::mock(MorphToMany::class);
        $relation->shouldReceive('wherePivot')->with('user_id', '!=', null)->andReturnSelf();
        $relation->shouldReceive('sum')->with('rating_morph.value')->andReturn('12.5');

        $action = new GetSumByModelRatingIdAction();
        $sum = $action->execute(ratingMockHost($relation));

        Assert::assertSame(12.5, $sum);
    });

    test('GetSumByModelRatingIdAction filtra per rating_id quando passato', function (): void {
        $relation = \Mockery::mock(MorphToMany::class);
        $relation->shouldReceive('wherePivot')->with('user_id', '!=', null)->andReturnSelf();
        $relation->shouldReceive('wherePivot')->with('rating_id', '9')->andReturnSelf();
        $relation->shouldReceive('sum')->with('rating_morph.value')->andReturn(3.0);

        $action = new GetSumByModelRatingIdAction();

        Assert::assertSame(3.0, $action->execute(ratingMockHost($relation), '9'));
    });

    test('GetSumByModelRatingIdAction restituisce zero se sum non numerico', function (): void {
        $relation = \Mockery::mock(MorphToMany::class);
        $relation->shouldReceive('wherePivot')->with('user_id', '!=', null)->andReturnSelf();
        $relation->shouldReceive('sum')->with('rating_morph.value')->andReturn(null);

        $action = new GetSumByModelRatingIdAction();

        Assert::assertSame(0.0, $action->execute(ratingMockHost($relation)));
    });

    test('GetRatingOptsByModelAction mappa id titolo', function (): void {
        $relation = \Mockery::mock(MorphToMany::class);
        $relation->shouldReceive('wherePivot')->with('user_id', null)->andReturnSelf();
        $relation->shouldReceive('pluck')->with('title', 'ratings.id')->andReturn(collect([
            3 => 'Ottimo',
            5 => 'Buono',
        ]));

        $action = new GetRatingOptsByModelAction();
        $opts = $action->execute(ratingMockHost($relation));

        Assert::assertSame([3 => 'Ottimo', 5 => 'Buono'], $opts);
    });
});
