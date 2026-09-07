<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit\Actions\SMS;

use Modules\Notify\Actions\SMS\SendAgiletelecomSMSv1Action;
use Modules\Notify\Contracts\SMS\SmsActionContract;
use Modules\Notify\Datas\SmsData;
use PHPUnit\Framework\Assert;
use ReflectionClass;
use ReflectionNamedType;

describe('SendAgiletelecomSMSv1Action', function () {
    it('can be instantiated', function () {
        Assert::assertTrue(class_exists(SendAgiletelecomSMSv1Action::class));
    });

    it('implements SmsActionContract', function () {
<<<<<<< HEAD
        $action = new SendAgiletelecomSMSv1Action;
=======
        $action = new SendAgiletelecomSMSv1Action();
>>>>>>> a988596b (first)

        Assert::assertInstanceOf(SmsActionContract::class, $action);
    });

    it('has execute method with correct signature', function () {
<<<<<<< HEAD
        $reflection = new ReflectionClass(new SendAgiletelecomSMSv1Action);
=======
        $reflection = new ReflectionClass(new SendAgiletelecomSMSv1Action());
>>>>>>> a988596b (first)
        $method = $reflection->getMethod('execute');

        expect($method->isPublic())->toBeTrue();
        expect($method->getNumberOfParameters())->toBe(1);
    });

    it('execute accepts SmsData parameter', function () {
<<<<<<< HEAD
        $reflection = new ReflectionClass(new SendAgiletelecomSMSv1Action);
=======
        $reflection = new ReflectionClass(new SendAgiletelecomSMSv1Action());
>>>>>>> a988596b (first)
        $method = $reflection->getMethod('execute');
        $params = $method->getParameters();
        $type = $params[0]->getType();

        expect($type)->toBeInstanceOf(ReflectionNamedType::class);
        expect($type instanceof ReflectionNamedType ? $type->getName() : '')->toBe(SmsData::class);
    });

    it('execute returns array', function () {
<<<<<<< HEAD
        $reflection = new ReflectionClass(new SendAgiletelecomSMSv1Action);
=======
        $reflection = new ReflectionClass(new SendAgiletelecomSMSv1Action());
>>>>>>> a988596b (first)
        $method = $reflection->getMethod('execute');
        $returnType = $method->getReturnType();

        expect($returnType)->toBeInstanceOf(ReflectionNamedType::class);
        expect($returnType instanceof ReflectionNamedType ? $returnType->getName() : '')->toBe('array');
    });

    it('uses strict types', function () {
<<<<<<< HEAD
        $reflection = new ReflectionClass(new SendAgiletelecomSMSv1Action);
=======
        $reflection = new ReflectionClass(new SendAgiletelecomSMSv1Action());
>>>>>>> a988596b (first)
        $filename = $reflection->getFileName();

        expect($filename)->not->toBeNull();
        /** @var string $filename */
        $content = \Safe\file_get_contents($filename);
        expect($content)->toContain('declare(strict_types=1)');
    });

    it('has correct namespace', function () {
<<<<<<< HEAD
        $reflection = new ReflectionClass(new SendAgiletelecomSMSv1Action);
=======
        $reflection = new ReflectionClass(new SendAgiletelecomSMSv1Action());
>>>>>>> a988596b (first)

        expect($reflection->getNamespaceName())->toBe('Modules\\Notify\\Actions\\SMS');
    });

    it('has required imports', function () {
<<<<<<< HEAD
        $reflection = new ReflectionClass(new SendAgiletelecomSMSv1Action);
=======
        $reflection = new ReflectionClass(new SendAgiletelecomSMSv1Action());
>>>>>>> a988596b (first)
        $filename = $reflection->getFileName();
        /** @var string $filename */
        $content = \Safe\file_get_contents($filename);

        expect($content)->toContain('use GuzzleHttp\\Client;');
        expect($content)->toContain('use Modules\\Notify\\Datas\\SMS\\AgiletelecomData;');
    });

    it('does not use QueueableAction trait', function () {
<<<<<<< HEAD
        $traits = \Safe\class_uses(new SendAgiletelecomSMSv1Action);

<<<<<<< HEAD
        expect($traits)->not->toContain('Spatie\\QueueableAction\\QueueableAction');

=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        $traits = \Safe\class_uses(new SendAgiletelecomSMSv1Action());

        expect($traits)->not->toContain('Spatie\\QueueableAction\\QueueableAction');

>>>>>>> a988596b (first)
        expect($traits)->toContain('Spatie\\QueueableAction\\QueueableAction');
    });
});
