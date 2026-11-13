<?php

declare(strict_types=1);
<<<<<<< HEAD

=======
>>>>>>> 77b1e2748 (first)
use Illuminate\Database\Eloquent\Model;
use Modules\Xot\Models\XotBaseModel;
use Modules\Xot\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

<<<<<<< HEAD
describe('XotBaseModel Business Logic', function () {
    test('xot base model extends eloquent model', function () {
        Assert::assertSame(Model::class, get_parent_class(XotBaseModel::class));
    });

    test('xot base model is abstract foundation for module models', function () {
        $reflection = new ReflectionClass(XotBaseModel::class);

        Assert::assertTrue($reflection->isAbstract());
        Assert::assertTrue($reflection->isSubclassOf(Model::class));
    });

    test('xot base model provides foundation for other models', function () {
=======
describe('XotBaseModel Business Logic', function (): void {
    test('xot base model extends eloquent model', function (): void {
        $reflection = new ReflectionClass(XotBaseModel::class);

        Assert::assertTrue($reflection->isSubclassOf(Model::class));
    });

    test('xot base model cannot be instantiated directly', function (): void {
        $reflection = new ReflectionClass(XotBaseModel::class);

        Assert::assertFalse($reflection->isInstantiable());
        Assert::assertTrue($reflection->isSubclassOf(Model::class));
    });

    test('xot base model provides foundation for other models', function (): void {
>>>>>>> 77b1e2748 (first)
        Assert::assertTrue(class_exists(XotBaseModel::class));
    });
});
