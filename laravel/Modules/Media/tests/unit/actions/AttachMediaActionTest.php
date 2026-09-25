<?php

declare(strict_types=1);

namespace Modules\Media\Tests\Unit\Actions;

uses(\Modules\Media\Tests\TestCase::class);

use Modules\Media\Actions\AttachMediaAction;
use Spatie\QueueableAction\QueueableAction;

describe('AttachMediaAction', function () {
    it('uses QueueableAction trait', function (): void {
        // Arrange
        $action = new AttachMediaAction;

        expect(class_uses_recursive($action))->toContain(QueueableAction::class);
    });

});
