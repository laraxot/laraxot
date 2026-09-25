<?php

declare(strict_types=1);

namespace Modules\Geo\Tests\Unit\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Modules\Geo\Models\Comune;
use Modules\Geo\Tests\TestCase;
use PHPUnit\Framework\Assert;

use function Safe\json_encode;

final class ComuneTest extends TestCase
{
    /** @var list<array<string, mixed>> */
    private array $comuneFixtureRows = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->comuneFixtureRows = [
            [
                'id' => 1,
                'regione' => 'Lombardia',
                'provincia' => 'Milano',
                'nome' => 'Milano',
                'cap' => '20100',
                'lat' => 45.4642,
                'lng' => 9.1900,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'regione' => 'Lombardia',
                'provincia' => 'Milano',
                'nome' => 'Sesto San Giovanni',
                'cap' => '20099',
                'lat' => 45.5347,
                'lng' => 9.2345,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        File::put(
            base_path('database/content/comuni.json'),
            json_encode($this->comuneFixtureRows, JSON_PRETTY_PRINT)
        );
    }

    protected function tearDown(): void
    {
        Cache::forget('sushi_Comune_data');
        File::delete(base_path('database/content/comuni.json'));
        parent::tearDown();
    }

    public function testItCanLoadComuniFromJson(): void
    {
        $comuni = Comune::all();

        Assert::assertCount(2, $comuni);
        /** @var Comune $first */
        $first = $comuni->first();
        Assert::assertSame('Milano', $first->nome);
        /** @var Comune $last */
        $last = $comuni->last();
        Assert::assertSame('Sesto San Giovanni', $last->nome);
    }

    public function testItCanFilterComuniByRegion(): void
    {
        /** @phpstan-ignore-next-line -- Dynamic scope */
        $comuni = Comune::byRegion('Lombardia')->get();

        /* @phpstan-ignore-next-line -- $comuni is mixed from ignored scope */
        Assert::assertCount(2, $comuni);
        /** @phpstan-ignore-next-line -- $comuni is mixed from ignored scope */
        $first = $comuni->first();
        /* @phpstan-ignore-next-line -- $first is mixed from ignored scope */
        Assert::assertSame('Lombardia', $first->regione);
        /** @phpstan-ignore-next-line -- $comuni is mixed from ignored scope */
        $last = $comuni->last();
        /* @phpstan-ignore-next-line -- $last is mixed from ignored scope */
        Assert::assertSame('Lombardia', $last->regione);
    }

    public function testItCanFilterComuniByProvince(): void
    {
        /** @phpstan-ignore-next-line -- Dynamic scope */
        $comuni = Comune::byProvince('Milano')->get();

        /* @phpstan-ignore-next-line -- $comuni is mixed from ignored scope */
        Assert::assertCount(2, $comuni);
        /** @phpstan-ignore-next-line -- $comuni is mixed from ignored scope */
        $first = $comuni->first();
        /* @phpstan-ignore-next-line -- $first is mixed from ignored scope */
        Assert::assertSame('Milano', $first->provincia);
        /** @phpstan-ignore-next-line -- $comuni is mixed from ignored scope */
        $last = $comuni->last();
        /* @phpstan-ignore-next-line -- $last is mixed from ignored scope */
        Assert::assertSame('Milano', $last->provincia);
    }

    public function testItCanFilterComuniByCap(): void
    {
        /** @phpstan-ignore-next-line -- Dynamic scope */
        $comuni = Comune::byCap('20100')->get();

        /* @phpstan-ignore-next-line -- $comuni is mixed from ignored scope */
        Assert::assertCount(1, $comuni);
        /** @phpstan-ignore-next-line -- $comuni is mixed from ignored scope */
        $first = $comuni->first();
        /* @phpstan-ignore-next-line -- $first is mixed from ignored scope */
        Assert::assertSame('20100', $first->cap);
    }

    public function testItCanFilterComuniByName(): void
    {
        /** @phpstan-ignore-next-line -- Dynamic scope */
        $comuni = Comune::byName('Milano')->get();

        /* @phpstan-ignore-next-line -- $comuni is mixed from ignored scope */
        Assert::assertCount(1, $comuni);
        /** @phpstan-ignore-next-line -- $comuni is mixed from ignored scope */
        $first = $comuni->first();
        /* @phpstan-ignore-next-line -- $first is mixed from ignored scope */
        Assert::assertSame('Milano', $first->nome);
    }

    public function testItCanFilterComuniByExactName(): void
    {
        /** @phpstan-ignore-next-line -- Dynamic scope */
        $comuni = Comune::byExactName('Milano')->get();

        /* @phpstan-ignore-next-line -- $comuni is mixed from ignored scope */
        Assert::assertCount(1, $comuni);
        /** @phpstan-ignore-next-line -- $comuni is mixed from ignored scope */
        $first = $comuni->first();
        /* @phpstan-ignore-next-line -- $first is mixed from ignored scope */
        Assert::assertSame('Milano', $first->nome);
    }

    public function testItCanFilterComuniByNameAndProvince(): void
    {
        /** @phpstan-ignore-next-line -- Dynamic scope */
        $comuni = Comune::byNameAndProvince('Milano', 'Milano')->get();

        /* @phpstan-ignore-next-line -- $comuni is mixed from ignored scope */
        Assert::assertCount(1, $comuni);
        /** @phpstan-ignore-next-line -- $comuni is mixed from ignored scope */
        $first = $comuni->first();
        /* @phpstan-ignore-next-line -- $first is mixed from ignored scope */
        Assert::assertSame('Milano', $first->nome);
        /* @phpstan-ignore-next-line -- $first is mixed from ignored scope */
        Assert::assertSame('Milano', $first->provincia);
    }

    public function testItCanFilterComuniByNameAndRegion(): void
    {
        /** @phpstan-ignore-next-line -- Dynamic scope */
        $comuni = Comune::byNameAndRegion('Milano', 'Lombardia')->get();

        /* @phpstan-ignore-next-line -- $comuni is mixed from ignored scope */
        Assert::assertCount(1, $comuni);
        /** @phpstan-ignore-next-line -- $comuni is mixed from ignored scope */
        $first = $comuni->first();
        /* @phpstan-ignore-next-line -- $first is mixed from ignored scope */
        Assert::assertSame('Milano', $first->nome);
        /* @phpstan-ignore-next-line -- $first is mixed from ignored scope */
        Assert::assertSame('Lombardia', $first->regione);
    }

    public function testItCanFilterComuniByNameProvinceAndRegion(): void
    {
        /** @phpstan-ignore-next-line -- Dynamic scope */
        $comuni = Comune::byNameProvinceAndRegion('Milano', 'Milano', 'Lombardia')->get();

        /* @phpstan-ignore-next-line -- $comuni is mixed from ignored scope */
        Assert::assertCount(1, $comuni);
        /** @phpstan-ignore-next-line -- $comuni is mixed from ignored scope */
        $first = $comuni->first();
        /* @phpstan-ignore-next-line -- $first is mixed from ignored scope */
        Assert::assertSame('Milano', $first->nome);
        /* @phpstan-ignore-next-line -- $first is mixed from ignored scope */
        Assert::assertSame('Milano', $first->provincia);
        /* @phpstan-ignore-next-line -- $first is mixed from ignored scope */
        Assert::assertSame('Lombardia', $first->regione);
    }

    public function testItCanFilterComuniByNameAndCap(): void
    {
        /** @phpstan-ignore-next-line -- Dynamic scope */
        $comuni = Comune::byNameAndCap('Milano', '20100')->get();

        /* @phpstan-ignore-next-line -- $comuni is mixed from ignored scope */
        Assert::assertCount(1, $comuni);
        /** @phpstan-ignore-next-line -- $comuni is mixed from ignored scope */
        $first = $comuni->first();
        /* @phpstan-ignore-next-line -- $first is mixed from ignored scope */
        Assert::assertSame('Milano', $first->nome);
        /* @phpstan-ignore-next-line -- $first is mixed from ignored scope */
        Assert::assertSame('20100', $first->cap);
    }

    public function testItCanFilterComuniByNameProvinceAndCap(): void
    {
        /** @phpstan-ignore-next-line -- Dynamic scope */
        $comuni = Comune::byNameProvinceAndCap('Milano', 'Milano', '20100')->get();

        /* @phpstan-ignore-next-line -- $comuni is mixed from ignored scope */
        Assert::assertCount(1, $comuni);
        /** @phpstan-ignore-next-line -- $comuni is mixed from ignored scope */
        $first = $comuni->first();
        /* @phpstan-ignore-next-line -- $first is mixed from ignored scope */
        Assert::assertSame('Milano', $first->nome);
        /* @phpstan-ignore-next-line -- $first is mixed from ignored scope */
        Assert::assertSame('Milano', $first->provincia);
        /* @phpstan-ignore-next-line -- $first is mixed from ignored scope */
        Assert::assertSame('20100', $first->cap);
    }

    public function testItCanFilterComuniByNameRegionAndCap(): void
    {
        /** @phpstan-ignore-next-line -- Dynamic scope */
        $comuni = Comune::byNameRegionAndCap('Milano', 'Lombardia', '20100')->get();

        /* @phpstan-ignore-next-line -- $comuni is mixed from ignored scope */
        Assert::assertCount(1, $comuni);
        /** @phpstan-ignore-next-line -- $comuni is mixed from ignored scope */
        $first = $comuni->first();
        /* @phpstan-ignore-next-line -- $first is mixed from ignored scope */
        Assert::assertSame('Milano', $first->nome);
        /* @phpstan-ignore-next-line -- $first is mixed from ignored scope */
        Assert::assertSame('Lombardia', $first->regione);
        /* @phpstan-ignore-next-line -- $first is mixed from ignored scope */
        Assert::assertSame('20100', $first->cap);
    }

    public function testItCanFilterComuniByNameProvinceRegionAndCap(): void
    {
        /** @phpstan-ignore-next-line -- Dynamic scope */
        $comuni = Comune::byNameProvinceRegionAndCap('Milano', 'Milano', 'Lombardia', '20100')->get();

        /* @phpstan-ignore-next-line -- $comuni is mixed from ignored scope */
        Assert::assertCount(1, $comuni);
        /** @phpstan-ignore-next-line -- $comuni is mixed from ignored scope */
        $first = $comuni->first();
        /* @phpstan-ignore-next-line -- $first is mixed from ignored scope */
        Assert::assertSame('Milano', $first->nome);
        /* @phpstan-ignore-next-line -- $first is mixed from ignored scope */
        Assert::assertSame('Milano', $first->provincia);
        /* @phpstan-ignore-next-line -- $first is mixed from ignored scope */
        Assert::assertSame('Lombardia', $first->regione);
        /* @phpstan-ignore-next-line -- $first is mixed from ignored scope */
        Assert::assertSame('20100', $first->cap);
    }

    public function testItCanCreateANewComune(): void
    {
        $comune = Comune::create([
            'regione' => 'Lombardia',
            'provincia' => 'Milano',
            'nome' => 'Bresso',
            'cap' => '20091',
            'lat' => 45.5389,
            'lng' => 9.1900,
        ]);

        Assert::assertNotNull($comune);
        Assert::assertSame('Bresso', $comune->nome);
        Assert::assertSame('Milano', $comune->provincia);
        Assert::assertSame('Lombardia', $comune->regione);
        Assert::assertSame('20091', $comune->cap);
        Assert::assertSame(45.5389, $comune->lat);
        Assert::assertSame(9.1900, $comune->lng);
    }

    public function testItCanUpdateAnExistingComune(): void
    {
        $comune = Comune::first();
        /* @phpstan-ignore-next-line -- $comune may be null from first() */
        $comune->update([
            'nome' => 'Milano Centro',
            'cap' => '20121',
        ]);

        /* @phpstan-ignore-next-line -- $comune may be null */
        Assert::assertSame('Milano Centro', $comune->nome);
        /* @phpstan-ignore-next-line -- $comune may be null */
        Assert::assertSame('20121', $comune->cap);
    }

    public function testItCanDeleteAnExistingComune(): void
    {
        $comune = Comune::first();
        /** @phpstan-ignore-next-line -- $comune may be null from first() */
        $id = $comune->id;

        /* @phpstan-ignore-next-line -- $comune may be null */
        $comune->delete();

        Assert::assertNull(Comune::find($id));
    }
}
