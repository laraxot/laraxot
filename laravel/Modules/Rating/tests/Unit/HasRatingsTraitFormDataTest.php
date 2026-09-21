<?php

declare(strict_types=1);

namespace Modules\Rating\Tests\Unit;

use Illuminate\Database\Eloquent\Relations\MorphPivot;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Mockery\MockInterface;
use Modules\Rating\Models\Rating;
use Modules\Rating\Tests\Fixtures\RatingsHostStub;
use Modules\Rating\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

require_once __DIR__.'/../Fixtures/RatingsHostStub.php';

afterEach(function (): void {
    \Mockery::close();
});

/*
 * Story Rating/5.145 — refactor: fill/save del pivot ratings.{id}.pivot.{value,note}
 * spostato da CompilaIndennitaResponsabilita (duplicato, parziale, con un cast a 0 buggato)
 * a due metodi generici del trait. Risolve anche il blocker IR/5.140 (issue
 * provtv/module_indennitaresponsabilita_fila5#36) sul pass-through di pivot.note.
 */
describe('HasRatingsTrait::hydrateRatingsFormData', function (): void {
    test('mappa value e note per ogni riga gia caricata', function (): void {
        $host = new RatingsHostStub();
        $host->setRelation('ratings', collect([
            (object) ['id' => 1, 'pivot' => (object) ['value' => 5, 'note' => null]],
            (object) ['id' => 2, 'pivot' => (object) ['value' => null, 'note' => null]],
        ]));

        $data = $host->hydrateRatingsFormData(['dal' => 'x']);

        Assert::assertSame('x', $data['dal']);
        Assert::assertSame([
            '1' => ['pivot' => ['value' => 5, 'note' => null]],
            '2' => ['pivot' => ['value' => null, 'note' => null]],
        ], $data['ratings']);
    });

    test('rimappa value null + note valorizzata sulla chiave "other" (altro)', function (): void {
        $host = new RatingsHostStub();
        $host->setRelation('ratings', collect([
            (object) ['id' => 3, 'pivot' => (object) ['value' => null, 'note' => 'motivo libero']],
        ]));

        $data = $host->hydrateRatingsFormData([]);

        Assert::assertSame([
            '3' => ['pivot' => ['value' => 'other', 'note' => 'motivo libero']],
        ], $data['ratings']);
    });

    test('non rimappa se value e un numero reale, anche zero', function (): void {
        $host = new RatingsHostStub();
        $host->setRelation('ratings', collect([
            (object) ['id' => 4, 'pivot' => (object) ['value' => 0, 'note' => 'commento']],
        ]));

        $data = $host->hydrateRatingsFormData([]);

        Assert::assertSame([
            '4' => ['pivot' => ['value' => 0, 'note' => 'commento']],
        ], $data['ratings']);
    });
});

describe('HasRatingsTrait::syncRatingsFormData', function (): void {
    test('scrive value numerico e note invariata', function (): void {
        /** @var MorphToMany<Rating, RatingsHostStub, MorphPivot, 'pivot'>&MockInterface $relation */
        $relation = \Mockery::mock(MorphToMany::class);
        $relation->shouldReceive('updateExistingPivot')
            ->once()
            ->with(7, ['value' => 5, 'note' => 'ok']);

        $host = new RatingsHostStub();
        $host->forcedMorph = $relation;

        $host->syncRatingsFormData([
            7 => ['pivot' => ['value' => 5, 'note' => 'ok']],
        ]);
    });

    test('normalizza a null la chiave "other" (altro), mai a zero', function (): void {
        /** @var MorphToMany<Rating, RatingsHostStub, MorphPivot, 'pivot'>&MockInterface $relation */
        $relation = \Mockery::mock(MorphToMany::class);
        $relation->shouldReceive('updateExistingPivot')
            ->once()
            ->with(8, ['value' => null, 'note' => 'motivo']);

        $host = new RatingsHostStub();
        $host->forcedMorph = $relation;

        $host->syncRatingsFormData([
            8 => ['pivot' => ['value' => 'other', 'note' => 'motivo']],
        ]);
    });

    test('normalizza a null un valore non numerico, mai a zero', function (): void {
        /** @var MorphToMany<Rating, RatingsHostStub, MorphPivot, 'pivot'>&MockInterface $relation */
        $relation = \Mockery::mock(MorphToMany::class);
        $relation->shouldReceive('updateExistingPivot')
            ->once()
            ->with(9, ['value' => null]);

        $host = new RatingsHostStub();
        $host->forcedMorph = $relation;

        $host->syncRatingsFormData([
            9 => ['pivot' => ['value' => 'garbage']],
        ]);
    });

    test('value null resta null (nessuna scelta), mai a zero', function (): void {
        /** @var MorphToMany<Rating, RatingsHostStub, MorphPivot, 'pivot'>&MockInterface $relation */
        $relation = \Mockery::mock(MorphToMany::class);
        $relation->shouldReceive('updateExistingPivot')
            ->once()
            ->with(10, ['value' => null]);

        $host = new RatingsHostStub();
        $host->forcedMorph = $relation;

        $host->syncRatingsFormData([
            10 => ['pivot' => ['value' => null]],
        ]);
    });
});
