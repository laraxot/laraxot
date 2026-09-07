<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Notify\Models\NotificationType;
<<<<<<< HEAD
<<<<<<< HEAD
use PHPUnit\Framework\Assert;

=======
use Modules\Notify\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class)->group('notify-db');

>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
use PHPUnit\Framework\Assert;

>>>>>>> a988596b (first)
describe('NotificationType Business Logic', function () {
    test('notification type extends eloquent model', function () {
        $reflection = new \ReflectionClass(NotificationType::class);
        $parent = $reflection->getParentClass();

        Assert::assertInstanceOf(\ReflectionClass::class, $parent);
        Assert::assertSame(Model::class, $parent->getName());
    });

    test('notification type has expected fillable fields', function () {
        $reflection = new \ReflectionClass(NotificationType::class);
        $property = $reflection->getProperty('fillable');
        $property->setAccessible(true);

        $expectedFillable = [
            'name',
            'description',
<<<<<<< HEAD
<<<<<<< HEAD
            'template'];
=======
            'template',
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'template'];
>>>>>>> a988596b (first)

        Assert::assertEquals($expectedFillable, $property->getValue($reflection->newInstanceWithoutConstructor()));
    });

    test('notification type model structure is correct', function () {
        // Verify class exists and extends Model
        Assert::assertTrue(class_exists(NotificationType::class));
    });
});
