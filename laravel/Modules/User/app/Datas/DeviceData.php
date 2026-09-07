<?php

/**
 * ---.
 */

declare(strict_types=1);

namespace Modules\User\Datas;

<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Database\Eloquent\Model;
=======
>>>>>>> f548be94 (.)
=======
=======
use Illuminate\Database\Eloquent\Model;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Spatie\LaravelData\Data;
use Webmozart\Assert\Assert;

/**
 * Undocumented class.
 */
class DeviceData extends Data
{
    /*
     * case ApplicationVersion = 'X-App-Version';
     * case Application = 'X-Application';
     * case DeviceId = 'X-Device-Id';
     * case NotificationCode = 'X-Notification-Code';
     * case OperatingSystem = 'X-Operating-System';
     * case SynchronizationId = 'X-Synchronization-Identifier';
     */
<<<<<<< HEAD
<<<<<<< HEAD
    public ?string $appVersion = null;

    // = 'X-App-Version';
    public ?string $application = null;

    // = 'X-Application';
    public ?string $deviceId = null;

    // = 'X-Device-Id';
    public ?string $notificationCode = null;

    // = 'X-Notification-Code';
    public ?string $operatingSystem = null;

    // = 'X-Operating-System';
    public ?string $synchronizationId = null; // = 'X-Synchronization-Identifier';

    public static function make(): self
    {
        $headers = collect(request()->header())->mapWithKeys(
            /**
             * @param  array<int, string|null>  $item
             */
            static function (array $item, string $key): array {
                if (Str::startsWith($key, 'X-')) {
                    // $key = Str::afterFirst($key, 'X-');
                    $key = Str::after($key, 'X-');
                }

                $key = Str::camel($key);

                return [$key => $item];
            }
        )->all();
=======
=======
>>>>>>> 87273113 (.)
    public null|string $appVersion = null;

    // = 'X-App-Version';
    public null|string $application = null;

    // = 'X-Application';
    public null|string $deviceId = null;

    // = 'X-Device-Id';
    public null|string $notificationCode = null;

    // = 'X-Notification-Code';
    public null|string $operatingSystem = null;

    // = 'X-Operating-System';
    public null|string $synchronizationId = null; // = 'X-Synchronization-Identifier';

    public static function make(): self
    {
        $headers = collect(request()->header())->mapWithKeys(static function ($item, $key): array {
            if (Str::startsWith($key, 'X-')) {
                // $key = Str::afterFirst($key, 'X-');
                $key = Str::after($key, 'X-');
            }

            $key = Str::camel($key);

            return [$key => $item];
        })->all();
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    public ?string $appVersion = null;

    // = 'X-App-Version';
    public ?string $application = null;

    // = 'X-Application';
    public ?string $deviceId = null;

    // = 'X-Device-Id';
    public ?string $notificationCode = null;

    // = 'X-Notification-Code';
    public ?string $operatingSystem = null;

    // = 'X-Operating-System';
    public ?string $synchronizationId = null; // = 'X-Synchronization-Identifier';

    public static function make(): self
    {
        $headers = collect(request()->header())->mapWithKeys(
            /**
             * @param  array<int, string|null>  $item
             */
            static function (array $item, string $key): array {
                if (Str::startsWith($key, 'X-')) {
                    // $key = Str::afterFirst($key, 'X-');
                    $key = Str::after($key, 'X-');
                }

                $key = Str::camel($key);

                return [$key => $item];
            }
        )->all();
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

        return self::from($headers);
    }

    public function isValid(): bool
    {
        return true;
    }

    public function getSynchronizationId(string $apiName): string
    {
<<<<<<< HEAD
<<<<<<< HEAD
        if (null !== $this->synchronizationId) {
=======
        if ($this->synchronizationId !== null) {
>>>>>>> f548be94 (.)
=======
        if ($this->synchronizationId !== null) {
=======
        if (null !== $this->synchronizationId) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            return $this->synchronizationId;
        }

        $synchronizationClass = config('morph_map.synchronization');
<<<<<<< HEAD
<<<<<<< HEAD
        if (null === $synchronizationClass) {
=======
        if ($synchronizationClass === null) {
>>>>>>> f548be94 (.)
=======
        if ($synchronizationClass === null) {
=======
        if (null === $synchronizationClass) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            $synchronizationClass = '\Modules\Egea\Models\Synchronization';
        }

        // fare contract
        // Assert::isInstanceOf($synchronizationClass,Model::class,'['.__LINE__.']['.class_basename($this).']');
        // $synchronization = Synchronization::create([
<<<<<<< HEAD
<<<<<<< HEAD
        /** @var class-string<Model> $synchronizationClass */
        /** @var Model $synchronization */
=======
        /**
         * @phpstan-ignore staticMethod.nonObject
         */
>>>>>>> f548be94 (.)
=======
        /**
         * @phpstan-ignore staticMethod.nonObject
         */
=======
        /** @var class-string<Model> $synchronizationClass */
        /** @var Model $synchronization */
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        $synchronization = $synchronizationClass::create([
            // $synchronization = Synchronization::create([
            'user_id' => auth()->id(),
            'mobile_device_id' => $this->deviceId,
            'application' => $this->application ?? 'No-Set',
            'application_version' => $this->appVersion ?? 'No-Set',
            'api_name' => $apiName,
            'called_at' => Carbon::now(),
            // fulfilled_at
        ]);
<<<<<<< HEAD
<<<<<<< HEAD
        Assert::object($synchronization);

        $syncId = $synchronization->getAttribute('id');
        Assert::string($syncId, __FILE__.':'.__LINE__.' - '.class_basename(self::class));
        $this->synchronizationId = $syncId;
=======
        Assert::string($synchronizationId = $synchronization->id, __FILE__ . ':' . __LINE__ . ' - ' . class_basename(__CLASS__));
        $this->synchronizationId = $synchronizationId;
>>>>>>> f548be94 (.)
=======
        Assert::string($synchronizationId = $synchronization->id, __FILE__ . ':' . __LINE__ . ' - ' . class_basename(__CLASS__));
        $this->synchronizationId = $synchronizationId;
=======
        Assert::object($synchronization);

        $syncId = $synchronization->getAttribute('id');
        Assert::string($syncId, __FILE__.':'.__LINE__.' - '.class_basename(self::class));
        $this->synchronizationId = $syncId;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

        return $this->synchronizationId;
    }

    /*
     * public function getModel(){
     * MobileDevice::firstOrCreate();
     * }
     */
}
