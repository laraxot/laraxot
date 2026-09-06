<?php

declare(strict_types=1);

namespace Modules\Media\Tests\Unit\Support\Ffmpeg;

use Modules\Media\Support\Ffmpeg\MediaExporterResolver;
use Modules\Media\Tests\TestCase;
use PHPUnit\Framework\Assert;
use ProtoneMedia\LaravelFFMpeg\Exporters\MediaExporter;
use RuntimeException;

uses(TestCase::class)->group('no-media-db');

describe('MediaExporterResolver', function (): void {
    it('returns the value unchanged when it is already a MediaExporter', function (): void {
        $exporter = \Mockery::mock(MediaExporter::class);

        Assert::assertSame($exporter, MediaExporterResolver::from($exporter));
    });

    it('rejects a value that is not a MediaExporter', function (): void {
        try {
            MediaExporterResolver::from('non-un-exporter');
            Assert::fail('Expected RuntimeException was not thrown.');
        } catch (RuntimeException $exception) {
            Assert::assertStringContainsString('MediaExporter', $exception->getMessage());
        }
    });

    it('names the received type in the error message for an object', function (): void {
        try {
            MediaExporterResolver::from(new \stdClass());
            Assert::fail('Expected RuntimeException was not thrown.');
        } catch (RuntimeException $exception) {
            Assert::assertStringContainsString('stdClass', $exception->getMessage());
        }
    });

    it('names the received type in the error message for a scalar', function (): void {
        try {
            MediaExporterResolver::from(42);
            Assert::fail('Expected RuntimeException was not thrown.');
        } catch (RuntimeException $exception) {
            Assert::assertStringContainsString('int', $exception->getMessage());
        }
    });

    it('rejects null', function (): void {
        try {
            MediaExporterResolver::from(null);
            Assert::fail('Expected RuntimeException was not thrown.');
        } catch (RuntimeException $exception) {
            Assert::assertStringContainsString('null', $exception->getMessage());
        }
    });
});
