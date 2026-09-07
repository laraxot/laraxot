<?php

/**
 * @see https://github.com/DutchCodingCompany/filament-socialite
 */

declare(strict_types=1);

namespace Modules\User\Actions;

<<<<<<< HEAD
<<<<<<< HEAD
=======
// use DutchCodingCompany\FilamentSocialite\FilamentSocialite;
use InvalidArgumentException;
use RuntimeException;
>>>>>>> f548be94 (.)
=======
// use DutchCodingCompany\FilamentSocialite\FilamentSocialite;
use InvalidArgumentException;
use RuntimeException;
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Jenssegers\Agent\Agent;
use Modules\User\Models\Device;
use Spatie\QueueableAction\QueueableAction;

class GetCurrentDeviceAction
{
    use QueueableAction;

<<<<<<< HEAD
<<<<<<< HEAD
    public function __construct(
        private readonly Agent $agent,
        private readonly Device $deviceModel,
    ) {
    }

    /**
     * Execute the action.
     */
    public function execute(?string $mobileId = null): Device
    {
        $deviceInfo = $this->getDeviceInfo();
        $browserInfo = $this->getBrowserInfo();

        if (null !== $mobileId) {
            if (empty($mobileId)) {
                throw new \InvalidArgumentException('L\'ID mobile non può essere vuoto');
            }

            $device = $this->deviceModel->firstOrCreate(['mobile_id' => $mobileId]);
            if (null === $device) {
                throw new \RuntimeException('Impossibile creare o trovare il dispositivo');
            }
            $device->update([...$deviceInfo, ...$browserInfo]);
=======
=======
>>>>>>> 87273113 (.)
    /**
     * Execute the action.
     */
    public function execute(null|string $mobile_id = null): Device
    {
        $agent = new Agent();

        $device = $agent->device();
        $platform = $agent->platform();
        $browser = $agent->browser();

        $data = [
            'device' => is_string($device) ? $device : 'unknown',
            'platform' => is_string($platform) ? $platform : 'unknown',
            'browser' => is_string($browser) ? $browser : 'unknown',
            'is_desktop' => $agent->isDesktop(),
            'is_mobile' => $agent->isMobile(),
            'is_tablet' => $agent->isTablet(),
            'is_phone' => $agent->isPhone(),
            'is_robot' => $agent->isRobot(),
        ];

        $up = [
            'version' => is_string($browser) ? $agent->version($browser) : 'unknown',
            'robot' => is_string($agent->robot()) ? $agent->robot() : 'unknown',
        ];

        if ($mobile_id !== null) {
            if (empty($mobile_id)) {
                throw new InvalidArgumentException('L\'ID mobile non può essere vuoto');
            }

            $device = Device::firstOrCreate(['mobile_id' => $mobile_id]);
            if ($device === null) {
                throw new RuntimeException('Impossibile creare o trovare il dispositivo');
            }
            $device->update([...$data, ...$up]);
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    public function __construct(
        private readonly Agent $agent,
        private readonly Device $deviceModel,
    ) {
    }

    /**
     * Execute the action.
     */
    public function execute(?string $mobileId = null): Device
    {
        $deviceInfo = $this->getDeviceInfo();
        $browserInfo = $this->getBrowserInfo();

        if (null !== $mobileId) {
            if (empty($mobileId)) {
                throw new \InvalidArgumentException('L\'ID mobile non può essere vuoto');
            }

            $device = $this->deviceModel->firstOrCreate(['mobile_id' => $mobileId]);
            if (null === $device) {
                throw new \RuntimeException('Impossibile creare o trovare il dispositivo');
            }
            $device->update([...$deviceInfo, ...$browserInfo]);
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

            return $device;
        }

<<<<<<< HEAD
<<<<<<< HEAD
        $device = $this->deviceModel->firstOrCreate($deviceInfo);
        if (null === $device) {
            throw new \RuntimeException('Impossibile creare o trovare il dispositivo');
        }
        $device->update($browserInfo);

        return $device;
    }

    /**
     * Get basic device information.
     *
     * @return array<string, mixed>
     */
    private function getDeviceInfo(): array
    {
        $device = $this->agent->device();
        $platform = $this->agent->platform();
        $browser = $this->agent->browser();

        return [
            'device' => is_string($device) ? $device : 'unknown',
            'platform' => is_string($platform) ? $platform : 'unknown',
            'browser' => is_string($browser) ? $browser : 'unknown',
            'is_desktop' => $this->agent->isDesktop(),
            'is_mobile' => $this->agent->isMobile(),
            'is_tablet' => $this->agent->isTablet(),
            'is_phone' => $this->agent->isPhone(),
            'is_robot' => $this->agent->isRobot(),
        ];
    }

    /**
     * Get browser version and robot information.
     *
     * @return array<string, mixed>
     */
    private function getBrowserInfo(): array
    {
        $browser = $this->agent->browser();
        $browserVersion = is_string($browser) ? $this->agent->version($browser) : 'unknown';

        return [
            'version' => is_string($browserVersion) ? $browserVersion : 'unknown',
            'robot' => is_string($this->agent->robot()) ? $this->agent->robot() : 'unknown',
        ];
    }
=======
=======
>>>>>>> 87273113 (.)
        $device = Device::firstOrCreate($data);
        if ($device === null) {
            throw new RuntimeException('Impossibile creare o trovare il dispositivo');
        }
        $device->update($up);

        return $device;
    }
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
        $device = $this->deviceModel->firstOrCreate($deviceInfo);
        if (null === $device) {
            throw new \RuntimeException('Impossibile creare o trovare il dispositivo');
        }
        $device->update($browserInfo);

        return $device;
    }

    /**
     * Get basic device information.
     *
     * @return array<string, mixed>
     */
    private function getDeviceInfo(): array
    {
        $device = $this->agent->device();
        $platform = $this->agent->platform();
        $browser = $this->agent->browser();

        return [
            'device' => is_string($device) ? $device : 'unknown',
            'platform' => is_string($platform) ? $platform : 'unknown',
            'browser' => is_string($browser) ? $browser : 'unknown',
            'is_desktop' => $this->agent->isDesktop(),
            'is_mobile' => $this->agent->isMobile(),
            'is_tablet' => $this->agent->isTablet(),
            'is_phone' => $this->agent->isPhone(),
            'is_robot' => $this->agent->isRobot(),
        ];
    }

    /**
     * Get browser version and robot information.
     *
     * @return array<string, mixed>
     */
    private function getBrowserInfo(): array
    {
        $browser = $this->agent->browser();
        $browserVersion = is_string($browser) ? $this->agent->version($browser) : 'unknown';

        return [
            'version' => is_string($browserVersion) ? $browserVersion : 'unknown',
            'robot' => is_string($this->agent->robot()) ? $this->agent->robot() : 'unknown',
        ];
    }
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
}
