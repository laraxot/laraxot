<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit\Models\Traits;

use Modules\Notify\Enums\ContactTypeEnum;
use Modules\Notify\Tests\Fixtures\HasContactDummyModel;
<<<<<<< HEAD
<<<<<<< HEAD
use PHPUnit\Framework\Assert;

=======
use Modules\Notify\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class)->group('no-notify-db');

>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
function makeHasContactDummyModel(): HasContactDummyModel
{
    return new HasContactDummyModel;
=======
use PHPUnit\Framework\Assert;

function makeHasContactDummyModel(): HasContactDummyModel
{
    return new HasContactDummyModel();
>>>>>>> a988596b (first)
}

test('has contact trait appends contact type fields to fillable', function (): void {
    $model = makeHasContactDummyModel();
    $model->initContactTrait();

    $fillable = $model->getFillable();

    foreach (ContactTypeEnum::cases() as $case) {
        Assert::assertContains($case->value, $fillable);
    }
});
