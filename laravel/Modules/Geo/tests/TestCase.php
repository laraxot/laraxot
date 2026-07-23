<?php

declare(strict_types=1);

namespace Modules\Geo\Tests;

use GuzzleHttp\Handler\MockHandler;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\ServiceProvider;
use Mockery\MockInterface;
use Modules\Geo\Actions\GoogleMapsAction;
use Modules\Geo\Models\Address;
use Modules\Geo\Models\BaseModel;
use Modules\Geo\Providers\GeoServiceProvider;
use Modules\User\Providers\UserServiceProvider;
use Modules\Xot\Datas\XotData;
use Modules\Xot\Tests\XotBaseTestCase;

/**
 * @property object|null           $action
 * @property MockInterface|null    $mockDistanceMatrixAction
 * @property MockInterface|null    $fetchAction
 * @property MockInterface|null    $mockClient
 * @property MockInterface|null    $getCoordinatesAction
 * @property MockHandler|null      $mockHandler
 * @property GoogleMapsAction|null $service
 * @property Address|null          $address
 * @property BaseModel|null        $baseModel
 * @property array<string, mixed>  $testData
 * @property array<string, mixed>  $italianAddress
 * @property array<string, mixed>  $geocodingResult
 * @property array<string, mixed>  $weatherData
 * @property array<string, mixed>  $place
 */
abstract class TestCase extends XotBaseTestCase
{
    use DatabaseTransactions;

    public ?MockInterface $mockDistanceMatrixAction = null;

    public ?MockInterface $fetchAction = null;

    public ?MockInterface $mockClient = null;

    public ?MockInterface $getCoordinatesAction = null;

    public ?MockHandler $mockHandler = null;

    public ?Address $address = null;

    /** @var array<string, mixed> */
    public array $testData = [];

    /** @var array<string, mixed> */
    public array $italianAddress = [];

    /** @var array<string, mixed> */
    public array $geocodingResult = [];

    /** @var array<string, mixed> */
    public array $weatherData = [];

    /** @var array<string, mixed> */
    public array $place = [];

    /** @var list<string> */
    protected $connectionsToTransact = [
        'mysql',
        'user',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        config(['xra.pub_theme' => 'Meetup']);
        config(['xra.main_module' => 'User']);

        XotData::make()->update([
            'pub_theme' => 'Meetup',
            'main_module' => 'User',
        ]);
    }

    /**
     * @return array<int, class-string<ServiceProvider>>
     */
    protected function getPackageProviders(Application $app): array
    {
        return [
            ...parent::getPackageProviders($app),
            UserServiceProvider::class,
            GeoServiceProvider::class,
        ];
    }
}
