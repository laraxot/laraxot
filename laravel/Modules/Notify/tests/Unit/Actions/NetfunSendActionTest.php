<?php

declare(strict_types=1);


namespace Modules\Notify\Tests\Unit\Actions;
use Modules\Notify\Actions\NetfunSendAction;
use Modules\Notify\Datas\SmsData;
use Modules\Notify\Tests\TestCase;

uses(TestCase::class);

describe('NetfunSendAction', function () {
    it('has execute method returning array', function () {
        $reflection = new \ReflectionClass(NetfunSendAction::class);
        $method = $reflection->getMethod('execute');

        \assertReflectionTypeName($method->getReturnType(), 'array');
        \assertReflectionTypeName($method->getParameters()[0]->getType(), SmsData::class);
    });

    it('uses strict types', function () {
        $content = \notifyReflectionSource(new \ReflectionClass(NetfunSendAction::class));
        expect($content)->toContain('declare(strict_types=1);');
    });

    it('uses QueueableAction trait', function () {
        $traits = \Safe\class_uses(NetfunSendAction::class);
        \assertNotifyArray($traits);
        expect($traits)->toContain('Spatie\QueueableAction\QueueableAction');
    });
});
