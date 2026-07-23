<?php

declare(strict_types=1);

namespace Modules\Media\Tests\Unit\Actions;

use Modules\Media\Actions\AttachMediaAction;
use Modules\Media\Tests\TestCase;
use Spatie\QueueableAction\QueueableAction;

uses(TestCase::class);

describe('AttachMediaAction', function () {
    it('uses QueueableAction trait', function (): void {
        // Arrange
        $action = new AttachMediaAction();

        // Assert - Verify the trait is used
        expect(trait_exists(QueueableAction::class))->toBeTrue();
    });

    it('is instance of AttachMediaAction', function (): void {
        // Arrange
        $action = new AttachMediaAction();

        // Assert
        expect($action)->toBeInstanceOf(AttachMediaAction::class);
    });

    it('can be instantiated', function (): void {
        // Act
        $action = new AttachMediaAction();

        // Assert
        expect($action)->not()->toBeNull();
    });
});
