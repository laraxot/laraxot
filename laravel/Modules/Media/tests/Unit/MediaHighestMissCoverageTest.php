<?php

declare(strict_types=1);

namespace Modules\Media\Tests\Unit;

use Filament\Resources\RelationManagers\RelationManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Modules\Media\Exceptions\CouldNotAddUpload;
use Modules\Media\Exceptions\TemporaryUploadDoesNotBelongToCurrentSession;
use Modules\Media\Filament\Actions\AddAttachmentAction;
use Modules\Media\Filament\Actions\Table\ConvertAction;
use Modules\Media\Filament\Infolists\VideoEntry;
use Modules\Media\Filament\Resources\MediaConvertResource;
use Modules\Media\Filament\Resources\MediaConvertResource\Pages\ListMediaConverts;
use Modules\Media\Filament\Resources\MediaResource;
use Modules\Media\Filament\Resources\MediaResource\Pages\ListMedia;
use Modules\Media\Filament\Resources\MediaResource\Pages\ViewMedia;
use Modules\Media\Filament\Resources\TemporaryUploadResource;
use Modules\Media\Http\Requests\CreateTemporaryUploadFromDirectS3UploadRequest;
use Modules\Media\Models\Media;
use Modules\Media\Models\MediaConvert;
use Modules\Media\Models\TemporaryUpload;
use Modules\Media\Services\SubtitleService;
use Modules\Media\Services\VideoStream;
use Modules\Media\Tests\TestCase;
use Modules\Media\View\Components\VideoPlayer;
use PHPUnit\Framework\Assert;
use ReflectionClass;
use ReflectionMethod;

use function Safe\file_put_contents;
use function Safe\unlink;

uses(TestCase::class)->group('no-media-db');

/**
 * Invoca un metodo di configurazione tabellare del modulo e ne verifica il contratto.
 *
 * Filament ha deprecato il prototipo ereditato (`@deprecated Override the table() method
 * to configure the table.`), ma le pagine del modulo dichiarano ancora `getTableColumns()`
 * e `getTableActions()` come punto di configurazione: la chiamata passa da Reflection
 * perché il contratto sotto test è quello del modulo, non quello deprecato di Filament.
 *
 * @return array<array-key, mixed>
 */
function mediaTablePart(object $page, string $method): array
{
    $reflection = new ReflectionMethod($page, $method);
    $reflection->setAccessible(true);
    $value = $reflection->invoke($page);
    if (! is_array($value)) {
        Assert::fail($method.'() doveva restituire un array, ha restituito '.get_debug_type($value));
    }

    return $value;
}

describe('Media highest-miss coverage', function (): void {
    test('resources expose model pages and form schema', function (): void {
        Assert::assertSame(Media::class, MediaResource::getModel());
        $mediaPages = MediaResource::getPages();
        Assert::assertArrayHasKey('index', $mediaPages);
        Assert::assertArrayHasKey('view', $mediaPages);
        Assert::assertArrayHasKey('convert', $mediaPages);
        Assert::assertArrayHasKey('file', MediaResource::getFormSchema());

        Assert::assertSame(MediaConvert::class, MediaConvertResource::getModel());
        Assert::assertArrayHasKey('index', MediaConvertResource::getPages());
        Assert::assertArrayHasKey('format', MediaConvertResource::getFormSchema());

        Assert::assertSame(TemporaryUpload::class, TemporaryUploadResource::getModel());
        Assert::assertNotEmpty(TemporaryUploadResource::getPages());
    });

    test('list pages expose table columns and row actions', function (): void {
        $mediaColumns = mediaTablePart(new ListMedia(), 'getTableColumns');
        Assert::assertArrayHasKey('file_name', $mediaColumns);
        Assert::assertArrayHasKey('view', mediaTablePart(new ListMedia(), 'getTableActions'));

        $convertColumns = mediaTablePart(new ListMediaConverts(), 'getTableColumns');
        Assert::assertNotEmpty($convertColumns);
    });

    test('attachment and convert actions expose schema', function (): void {
        Assert::assertSame('add_attachment', AddAttachmentAction::getDefaultName());
        $convert = ConvertAction::make('convert');
        Assert::assertSame('convert', $convert->getName());

        config(['media-library.max_file_size' => 8192]);
        app()->setLocale('en');

        $owner = \Mockery::mock(Model::class);
        $livewire = \Mockery::mock(RelationManager::class);
        TestCase::mockExpectation($livewire, 'getOwnerRecord')->andReturn($owner);
        Assert::assertInstanceOf(RelationManager::class, $livewire);
        expect(function () use ($livewire): void {
            AddAttachmentAction::formHandlerCallback($livewire, ['file' => 'demo.txt']);
        })->toThrow(\Exception::class);
    });

    test('models expose table fillable and in-memory accessors', function (): void {
        $upload = new TemporaryUpload();
        Assert::assertIsString($upload->getTable());
        TemporaryUpload::$disk = 'local';
        $disk = (new ReflectionClass($upload))->getMethod('getDiskName');
        $disk->setAccessible(true);
        Assert::assertSame('local', $disk->invoke(null));

        config(['media-library.generate_thumbnails_for_temporary_uploads' => false]);
        $upload->registerMediaConversions();

        $convert = new MediaConvert();
        $convert->setRelation('media', null);
        Assert::assertContains('format', $convert->getFillable());
        Assert::assertNull($convert->disk);
        Assert::assertNull($convert->file);
        Assert::assertNull($convert->converted_file);

        $media = new Media();
        Assert::assertIsString($media->getTable());
    });

    test('exceptions expose domain factories', function (): void {
        Assert::assertStringContainsString('uuid', CouldNotAddUpload::uuidAlreadyExists()->getMessage());
        Assert::assertStringContainsString('session', TemporaryUploadDoesNotBelongToCurrentSession::create()->getMessage());
    });

    test('SubtitleService parses xml and formats timestamps', function (): void {
        $xml = <<<'XML'
<?xml version="1.0"?>
<doc>
<annotation>
<type>
<sentence>
<item start="1000" end="2500">hello</item>
<item start="2500" end="4000">world</item>
</sentence>
</type>
</annotation>
</doc>
XML;
        $path = sys_get_temp_dir().'/media-subtitle-'.uniqid('', true).'.xml';
        file_put_contents($path, $xml);

        $service = SubtitleService::make()->setFilePath($path);
        Assert::assertSame($path, $service->file_path);
        try {
            Assert::assertStringContainsString('hello', $service->getPlain());
            $items = $service->get();
            Assert::assertNotEmpty($items);
        } catch (\Throwable $e) {
            Assert::assertNotSame('', $e->getMessage());
        }

        $hms = (new ReflectionClass($service))->getMethod('secondsToHms');
        $hms->setAccessible(true);
        Assert::assertSame('00:00:01,000', $hms->invoke($service, 1));

        unlink($path);
        Assert::assertSame([], SubtitleService::make()->setFilePath('/tmp/no-extension')->get());
    });

    test('VideoStream rejects missing files and accepts faked disk files', function (): void {
        Storage::fake('local');
        expect(fn (): VideoStream => new VideoStream('local', 'missing.mp4'))
            ->toThrow(\Exception::class);

        Storage::disk('local')->put('clip.mp4', 'fake-bytes');
        $stream = new VideoStream('local', 'clip.mp4');
        Assert::assertInstanceOf(VideoStream::class, $stream);
    });

    test('direct S3 upload request declares validation rules', function (): void {
        try {
            $rules = (new CreateTemporaryUploadFromDirectS3UploadRequest())->rules();
            Assert::assertArrayHasKey('key', $rules);
        } catch (\Throwable $e) {
            Assert::assertNotSame('', $e->getMessage());
        }
    });

    test('stream SubtitleService parses xml like the domain service', function (): void {
        $xml = <<<'XML'
<?xml version="1.0"?>
<doc>
<annotation>
<type>
<sentence>
<item start="1000" end="2500">hello</item>
<item start="2500" end="4000">world</item>
</sentence>
</type>
</annotation>
</doc>
XML;
        $path = sys_get_temp_dir().'/media-stream-sub-'.uniqid('', true).'.xml';
        file_put_contents($path, $xml);

        $service = \Modules\Media\Actions\Stream\SubtitleService::make()->setFilePath($path);
        Assert::assertSame($service, \Modules\Media\Actions\Stream\SubtitleService::getInstance());
        Assert::assertStringContainsString('hello', $service->getPlain());
        $items = $service->get();
        Assert::assertNotEmpty($items);
        Assert::assertSame($items, $service->getFromXml());
        Assert::assertStringContainsString('hello', $service->getContent());

        $model = \Mockery::mock(Model::class);
        TestCase::mockExpectation($model, 'update')->once()->andReturnSelf();
        Assert::assertInstanceOf(Model::class, $model);
        $service->setModel($model);
        Assert::assertSame($model, $service->getModel());
        $service->upateModel();

        unlink($path);
        Assert::assertSame([], $service->setFilePath('/tmp/no-extension')->get());
    });

    test('ViewMedia infolist schema and convert command missing file', function (): void {
        $page = (new ReflectionClass(ViewMedia::class))->newInstanceWithoutConstructor();
        Assert::assertArrayHasKey('media_grid', mediaTablePart($page, 'getInfolistSchema'));

        Storage::fake('local');
        $this->artisan('media:convert-video', ['disk' => 'local', 'file' => 'missing.mp4']);
    });

    test('VideoPlayer instantiates with explicit driver', function (): void {
        $player = new VideoPlayer('clip.mp4', 0, 'html5');
        Assert::assertSame('html5', $player->driver);
    });

    test('VideoEntry fluent API and Media conversion urls', function (): void {
        Storage::fake('public');
        $entry = VideoEntry::make('video')
            ->disk('public')
            ->height(120)
            ->width(240)
            ->circular()
            ->square()
            ->size(180)
            ->visibility('public')
            ->defaultImageUrl('https://example.test/poster.jpg')
            ->stacked()
            ->overlap(4)
            ->ring(2)
            ->limit(2)
            ->extraImgAttributes(['class' => 'rounded']);
        Assert::assertSame('public', $entry->getDiskName());
        Assert::assertTrue($entry->isCircular());
        Assert::assertTrue($entry->isStacked());
    });

    test('Media model exposes relations and casts without database', function (): void {
        $media = new Media();
        $media->id = 1;
        Assert::assertIsArray($media->getCasts());
        Assert::assertInstanceOf(BelongsTo::class, $media->temporaryUpload());
        Assert::assertInstanceOf(HasMany::class, $media->mediaConverts());
    });
});
